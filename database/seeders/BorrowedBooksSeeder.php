<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BorrowedBook;
use App\Models\Book;
use App\Models\User;
use Carbon\Carbon;

class BorrowedBooksSeeder extends Seeder
{
    public function run()
    {
        $books = Book::take(5)->get();
        $user = User::first();

        if ($books->isEmpty()) {
            $this->command->info('لا توجد كتب — شغّل BooksSeeder أولاً.');
            return;
        }

        $now = Carbon::create(2025, 8, 10, 10, 0, 0);

        $entries = [
            ['book' => $books->get(0), 'borrower' => 'أحمد', 'days' => 14],
            ['book' => $books->get(1), 'borrower' => 'سارة', 'days' => 7],
            ['book' => $books->get(2), 'borrower' => 'خالد', 'days' => 30],
            ['book' => $books->get(3), 'borrower' => 'ليلى', 'days' => 10],
            ['book' => $books->get(4), 'borrower' => 'محمود', 'days' => 5],
        ];

        foreach ($entries as $e) {
            BorrowedBook::create([
                'user_id' => $user ? $user->id : null,
                'book_id' => $e['book']->id,
                'borrow_date' => $now->toDateTimeString(),
                'due_date' => $now->copy()->addDays($e['days'])->toDateTimeString(),
                'borrower_name' => $e['borrower'],
                'book_status' => 'borrowed',
                'created_at' => $now->toDateTimeString(),
                'updated_at' => $now->toDateTimeString(),
            ]);
            $now->addDays(2);
        }
    }
}
