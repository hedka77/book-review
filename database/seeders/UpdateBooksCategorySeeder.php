<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateBooksCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [ 'scifi', 'horror', 'romance', 'history', 'tech' ];

        Book::whereNull('category')->each(function($book) use ($categories)
        {
            $book->update([ 'category' => $categories[array_rand($categories)] ]);
        });
    }
}
