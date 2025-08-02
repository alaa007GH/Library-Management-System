<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use ReflectionMethod;
use ReflectionClass;
use ReflectionProperty;

class GenerateCollection extends Command
{
    protected $signature = 'generate:collection';
    protected $description = 'Generate or update a Postman collection from Laravel routes using route:list';

    public function handle()
    {
        $this->info('Generating or updating Postman collection from route:list...');

        // Step 1: Get and save route list
        $routes = $this->getAndSaveRoutes();

        // Step 2: Load existing Postman collection (if exists)
        $existingCollection = $this->loadExistingCollection();

        // Step 3: Build or update Postman collection
        $collection = $this->buildPostmanCollection($routes, $existingCollection);

        // Step 4: Save the updated collection to the project root
        $outputPath = base_path('postman_collection.json');
        File::put($outputPath, json_encode($collection, JSON_PRETTY_PRINT));
        $this->info("Postman collection generated/updated at: $outputPath");

        // Step 5: Clean up temporary route list file
        $tempPath = storage_path('app/route_list.json');
        if (File::exists($tempPath)) {
            File::delete($tempPath);
            $this->info("Temporary route list file deleted: $tempPath");
        }
    }

    protected function getAndSaveRoutes()
    {
        // Run `php artisan route:list --json` and save output
        Artisan::call('route:list', ['--json' => true]);
        $routesJson = Artisan::output();
        $tempPath = storage_path('app/route_list.json');
        File::put($tempPath, $routesJson);
        $this->info("Route list saved temporarily at: $tempPath");

        // Parse the saved JSON
        $routes = json_decode($routesJson, true);

        // Filter routes to include only those starting with 'api/' and not using Closure
        $routes = array_filter($routes, function ($route) {
            return str_starts_with($route['uri'], 'api/') && $route['action'] !== 'Closure';
        });

        // Add unique _route_id to each route
        foreach ($routes as &$route) {
            $route['_route_id'] = md5($route['method'] . '|' . $route['uri'] . '|' . $route['action']);
        }

        return $routes;
    }

    protected function loadExistingCollection()
    {
        $path = base_path('postman_collection.json');
        if (File::exists($path)) {
            $content = File::get($path);
            $collection = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $collection;
            }
            $this->warn("Invalid JSON in existing collection at $path. Starting with a new collection.");
        }
        return null;
    }

    protected function buildPostmanCollection($routes, $existingCollection)
    {
        // Initialize collection
        $collection = [
            'info' => [
                'name' => 'Laravel API Collection',
                'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
            ],
            'item' => [],
            'variable' => [
                ['key' => 'baseUrl', 'value' => 'http://localhost:8000'],
                ['key' => 'id', 'value' => '1'],
                ['key' => 'token', 'value' => 'your-auth-token'],
            ],
        ];

        // If there's an existing collection, preserve its info and variables
        if ($existingCollection) {
            $collection['info'] = $existingCollection['info'];
            $collection['variable'] = $existingCollection['variable'] ?? $collection['variable'];
        }

        // Group routes by controller
        $controllerGroups = [];
        foreach ($routes as $route) {
            if (!isset($route['action']) || !str_contains($route['action'], '@')) {
                continue; // Skip routes without controller@method
            }

            [$controller, $method] = explode('@', $route['action']);
            $controllerName = $this->getControllerFolderName($controller);

            if (!isset($controllerGroups[$controllerName])) {
                $controllerGroups[$controllerName] = [];
            }

            $controllerGroups[$controllerName][] = $route;
        }

        // Get existing requests from collection
        $existingRequests = $this->getExistingRequests($existingCollection);

        // Build or update Postman folders
        foreach ($controllerGroups as $controllerName => $controllerRoutes) {
            $folder = ['name' => $controllerName, 'item' => []];

            foreach ($controllerRoutes as $route) {
                $routeId = $route['_route_id'];
                // Check if the request already exists in the collection
                if (isset($existingRequests[$routeId])) {
                    // Keep the existing request to preserve manual changes (e.g., tests, descriptions)
                    $folder['item'][] = $existingRequests[$routeId];
                    unset($existingRequests[$routeId]); // Remove from existing to track deleted routes
                } else {
                    // Generate new request for new route
                    $request = $this->buildPostmanRequest($route);
                    $folder['item'][] = $request;
                    $this->info("Added new route: {$route['method']} {$route['uri']} ($routeId)");
                }
            }

            // Only add folder if it contains requests
            if (!empty($folder['item'])) {
                $collection['item'][] = $folder;
            }
        }

        // Warn about deleted routes
        foreach ($existingRequests as $routeId => $request) {
            $this->warn("Removed route: {$request['name']} ($routeId)");
        }

        return $collection;
    }

    protected function getExistingRequests($collection)
    {
        $requests = [];
        if ($collection && isset($collection['item'])) {
            foreach ($collection['item'] as $folder) {
                if (isset($folder['item'])) {
                    foreach ($folder['item'] as $request) {
                        if (isset($request['request']['_route_id'])) {
                            $requests[$request['request']['_route_id']] = $request;
                        }
                    }
                }
            }
        }
        return $requests;
    }

    protected function getControllerFolderName($controller)
    {
        // Remove namespace and 'Controller' suffix
        $parts = explode('\\', $controller);
        $controllerName = end($parts);
        return str_replace('Controller', '', $controllerName);
    }

    protected function buildPostmanRequest($route)
    {
        $method = explode('|', $route['method'])[0]; // Handle multiple methods (e.g., GET|HEAD)
        $uri = '/' . ltrim($route['uri'], '/');
        $name = $this->getRequestName($route);

        $request = [
            'name' => $name,
            'request' => [
                'method' => strtoupper($method),
                'header' => [['key' => 'Content-Type', 'value' => 'application/json']],
                'url' => [
                    'raw' => '{{baseUrl}}' . $uri,
                    'host' => ['{{baseUrl}}'],
                    'path' => explode('/', ltrim($uri, '/')),
                ],
                'description' => 'Generated by Laravel route:list. ' . ($route['name'] ? "Route name: {$route['name']}" : ''),
                '_route_id' => $route['_route_id'], // Unique identifier for the route
            ],
            'response' => [],
        ];

        // Add Authorization header for routes with auth middleware
        if (isset($route['middleware']) && (in_array('api', (array) $route['middleware']) || in_array('auth:sanctum', (array) $route['middleware']))) {
            $request['request']['header'][] = ['key' => 'Authorization', 'value' => 'Bearer {{token}}'];
            $request['request']['description'] .= "\nRequires authentication. Set the `token` variable in Postman.";
        }

        // For POST and PUT/PATCH, check for request class or model fillable and add body
        if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
            $body = $this->getRequestBody($route['action']);
            if ($body) {
                $request['request']['body'] = [
                    'mode' => 'raw',
                    'raw' => json_encode($body, JSON_PRETTY_PRINT),
                    'options' => ['raw' => ['language' => 'json']],
                ];
            } else {
                $request['request']['description'] .= "\nNo request body fields could be determined. Please provide the required fields manually.";
            }
        }

        return $request;
    }

    protected function getRequestName($route)
    {
        $action = str_replace('Controller@', ' ', $route['action']);
        $action = str_replace('App\\Http\\Controllers\\', '', $action);
        $name = $route['name'] ?? $action;
        $name = str_replace('.', ' ', $name);
        return ucwords(trim(str_replace(['@'], ' ', $name)));
    }

    protected function getRequestBody($action)
    {
        [$controller, $method] = explode('@', $action);
        $controllerName = $this->getControllerFolderName($controller);

        try {
            // Step 1: Check if controller file exists
            $controllerPath = str_replace('App\\Http\\Controllers\\', '', $controller);
            $controllerPath = str_replace('\\', '/', $controllerPath);
            $controllerFile = app_path('Http/Controllers/' . $controllerPath . '.php');

            if (!file_exists($controllerFile)) {
                $this->warn("Controller file not found for $action");
                return $this->getModelFillable($controllerName);
            }

            // Step 2: Use Reflection to inspect the controller method
            $reflection = new ReflectionMethod($controller, $method);
            $parameters = $reflection->getParameters();

            foreach ($parameters as $parameter) {
                // Check if parameter is a FormRequest class
                if ($parameter->getType() && strpos($parameter->getType()->getName(), 'App\\Http\\Requests\\') === 0) {
                    $requestClass = $parameter->getType()->getName();
                    $reflectionClass = new ReflectionClass($requestClass);

                    // Check for static::$rules property first to avoid dynamic logic
                    if ($reflectionClass->hasProperty('rules')) {
                        $property = $reflectionClass->getProperty('rules');
                        $property->setAccessible(true);
                        $rules = $property->isStatic() ? $property->getValue() : null;
                        if (is_array($rules) && !empty($rules)) {
                            $body = [];
                            foreach ($rules as $field => $rule) {
                                // Skip if rule is an object (custom rule)
                                if (is_object($rule)) {
                                    continue;
                                }
                                $body[$field] = ''; // Empty string as placeholder
                            }
                            if (!empty($body)) {
                                return $body;
                            }
                        }
                    }

                    // Check if rules() method exists
                    if ($reflectionClass->hasMethod('rules')) {
                        $rulesMethod = $reflectionClass->getMethod('rules');
                        $rulesMethod->setAccessible(true);
                        $request = app($requestClass); // Create instance without invoking logic
                        try {
                            $rules = $rulesMethod->invoke($request);
                            if (is_array($rules) && !empty($rules)) {
                                $body = [];
                                foreach ($rules as $field => $rule) {
                                    // Skip if rule is an object (custom rule)
                                    if (is_object($rule)) {
                                        continue;
                                    }
                                    $body[$field] = ''; // Empty string as placeholder
                                }
                                if (!empty($body)) {
                                    return $body;
                                }
                            }
                        } catch (\Exception $e) {
                            $this->warn("Could not process rules() for $requestClass in $action: " . $e->getMessage());
                            // Fallback to model fillable
                            return $this->getModelFillable($controllerName);
                        }
                    }
                }
            }

            // Step 3: Fallback to controller static::$rules
            $reflectionClass = new ReflectionClass($controller);
            if ($reflectionClass->hasProperty('rules')) {
                $property = $reflectionClass->getProperty('rules');
                $property->setAccessible(true);
                $rules = $property->isStatic() ? $property->getValue() : null;
                if (is_array($rules) && !empty($rules)) {
                    $body = [];
                    foreach ($rules as $field => $rule) {
                        // Skip if rule is an object (custom rule)
                        if (is_object($rule)) {
                            continue;
                        }
                        $body[$field] = ''; // Empty string as placeholder
                    }
                    if (!empty($body)) {
                        return $body;
                    }
                }
            }

            // Step 4: Fallback to model fillable
            return $this->getModelFillable($controllerName);

        } catch (\Exception $e) {
            $this->warn("Could not process request body for $action: " . $e->getMessage());
            // Fallback to model fillable
            return $this->getModelFillable($controllerName);
        }
    }

    protected function getModelFillable($controllerName)
    {
        try {
            // Construct model class name dynamically (assumes models are in App\Models)
            $modelClass = "App\\Models\\{$controllerName}";

            // Check if model class exists
            if (!class_exists($modelClass)) {
                $this->warn("Model $modelClass not found for controller $controllerName");
                return null;
            }

            // Use Reflection to inspect the model
            $reflectionClass = new ReflectionClass($modelClass);
            if ($reflectionClass->hasProperty('fillable')) {
                $property = $reflectionClass->getProperty('fillable');
                $property->setAccessible(true);
                $fillable = $property->isStatic() ? $property->getValue() : $property->getValue(app($modelClass));
                if (is_array($fillable) && !empty($fillable)) {
                    $body = [];
                    foreach ($fillable as $field) {
                        $body[$field] = ''; // Empty string as placeholder
                    }
                    return $body;
                }
            }

            $this->warn("No fillable fields found in model $modelClass");
            return null;
        } catch (\Exception $e) {
            $this->warn("Could not process fillable fields for $modelClass: " . $e->getMessage());
            return null;
        }
    }
}
?>
