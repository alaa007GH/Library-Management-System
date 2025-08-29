<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category; // لا تنسى استيراد الموديل
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // إنشاء يوزر افتراضي
        User::firstOrCreate(
            ['email' => 'admin@example.com'], 
            [
                'first_name' => 'Default',
                'last_name' => 'User',
                'phone_number' => '0000000000',
                'address' => 'Default Address',
                'password' => Hash::make('password123'),
            ]
        );

        // إنشاء أصناف كتب افتراضية
        $categories = [
            'روايات',
            'علوم',
            'رياضيات',
            'تاريخ',
            'تكنولوجيا',
            'أطفال',
            'فن',
            'سفر',
            'طبخ',
            'دين وفكر'
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
