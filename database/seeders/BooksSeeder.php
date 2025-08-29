<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;
use Carbon\Carbon;

class BooksSeeder extends Seeder
{
    public function run()
    {
        // تأكد من وجود أصناف؛ إن لم توجد أنشئ صنف افتراضي واحد
        $categories = Category::pluck('id')->toArray();
        if (empty($categories)) {
            $cat = Category::firstOrCreate(['name' => 'عام']);
            $categories = [$cat->id];
        }

        $books = [
            ['book_title' => 'قواعد البرمجة', 'author' => 'أحمد علي', 'price' => 45.00, 'image' => '', 'pdf' => '', 'description' => 'كتاب يشرح أساسيات البرمجة.'],
            ['book_title' => 'تعلم React', 'author' => 'ليلى سمير', 'price' => 60.00, 'image' => '', 'pdf' => '', 'description' => 'دليل عملي لتعلم React.'],
            ['book_title' => 'خوارزميات سهلة', 'author' => 'سارة محمد', 'price' => 55.00, 'image' => '', 'pdf' => '', 'description' => 'تبسيط لمفاهيم الخوارزميات.'],
            ['book_title' => 'قصة الحاسوب', 'author' => 'محمود حسن', 'price' => 30.00, 'image' => '', 'pdf' => '', 'description' => 'تاريخ الحواسيب وقصص مثيرة.'],
            ['book_title' => 'مدخل إلى C#', 'author' => 'خالد يوسف', 'price' => 70.00, 'image' => '', 'pdf' => '', 'description' => 'مقدمة شاملة للغة C#.'],
            ['book_title' => 'تصميم واجهات', 'author' => 'نورا إبراهيم', 'price' => 50.00, 'image' => '', 'pdf' => '', 'description' => 'مبادئ تصميم الواجهات وتجربة المستخدم.'],
        ];

        foreach ($books as $b) {
            Book::firstOrCreate(
                ['book_title' => $b['book_title']],
                [
                    'author' => $b['author'],
                    'price' => $b['price'],
                    // استخدم '' بدل null كي لا يخالف قيد NOT NULL
                    'image' => $b['image'],
                    'pdf' => $b['pdf'],
                    'description' => $b['description'] ?? 'لا يوجد وصف متاح.',
                    'category_id' => $categories[array_rand($categories)],
                    'discount' => 0,
                    'created_at' => Carbon::now()->subMonths(rand(1, 10))->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ]
            );
        }
    }
}
