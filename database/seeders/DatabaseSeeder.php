<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء يوزر افتراضي (admin)
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

        // أصناف افتراضية
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

        // استدعاء seeders المخصصة
        $this->call([
            BooksSeeder::class,
            PurchasesSeeder::class,
            BorrowedBooksSeeder::class,
        ]);
    }
}
