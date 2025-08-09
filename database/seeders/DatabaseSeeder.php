<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // TODO move to separate seed file and create factory for books
        $books = [
            [
                'title' => 'Clean Code',
                'isbn' => '9780132350884',
                'stock' => 10,
            ],
            [
                'title' => 'The Pragmatic Programmer',
                'isbn' => '9780201616224',
                'stock' => 15,
            ],
            [
                'title' => 'Design Patterns: Elements of Reusable Object-Oriented Software',
                'isbn' => '9780201633610',
                'stock' => 7,
            ],
            [
                'title' => 'Introduction to Algorithms',
                'isbn' => '9780262033848',
                'stock' => 5,
            ],
            [
                'title' => 'Laravel Up & Running',
                'isbn' => '9781491936085',
                'stock' => 12,
            ],
        ];

        Book::insert($books);
    }
}
