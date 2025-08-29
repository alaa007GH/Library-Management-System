<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Book;
use App\Models\User;

class PurchasesSeeder extends Seeder
{
    public function run(): void
    {
        $books = Book::pluck('id')->toArray();
        $users = User::pluck('id')->toArray();
        $purchases = [];

        for ($i = 1; $i <= 50; $i++) {
            $bookId = $books[array_rand($books)];
            $userId = $users[array_rand($users)];
            $quantity = rand(1, 3);
            $unitPrice = rand(10, 100);
            $totalPrice = $unitPrice * $quantity;

            $purchaseDate = Carbon::now()
                ->subDays(rand(0, 60))
                ->setTime(rand(8, 20), rand(0, 59));

            $purchases[] = [
                'book_id'       => $bookId,
                'user_id'       => $userId,
                'quantity'      => $quantity,
                'unit_price'    => $unitPrice,
                'total_price'   => $totalPrice,
                'purchase_date' => $purchaseDate,
                'created_at'    => $purchaseDate,
                'updated_at'    => $purchaseDate,
            ];
        }

        DB::table('purchases')->insert($purchases);
    }
}
