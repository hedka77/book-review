<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title    = $request->input('title');
        $filter   = $request->input('filter', '');
        $category = $request->input('category', '');
        //$page   = $request->has('page') ? $request->query('page') : 1;
        $page = $request->query('page') ?? 1;

        $books = Book::when($title, static function($query) use ($title)
        {
            return $query->title($title);

        })->when($category, static function($query) use ($category)
        {
            return $query->category($category);
        });

        /*$books = Book::when($title, static function($query, $title) {
            return $query->title($title);
        })->get();*/

        $books = match ($filter)
        {
            'popular_last_month'        => $books->popularLastMonth(),
            'popular_last_6months'      => $books->popularLast6Months(),
            'highest_rated_last_month'  => $books->highestRatedLastMonth(),
            'highest_rated_last_6month' => $books->highestRatedLast6Months(),
            default                     => $books->latest()->withAvgRating()->withReviewsCount()
        };

        //$books = $books->get();
        //$books = $books->paginate(10);

        $cacheKey = 'books:' . $filter . ':' . $category . ':' . $title . ':' . $page;

        //$books = Cache::remember($cacheKey, 3600, static fn() => $books->get());

        $books = Cache()->remember($cacheKey, 3600, function() use ($books)
        {
            return $books->paginate(10);
        });

        return view('books.index', [ 'books' => $books ]);
        //return view('book.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    /*public function show(Book $book)
    {
        $cacheKey = 'book:' . $book->id;

        $book = Cache()->remember($cacheKey, 3600, fn() => $book->load([
            'reviews' => fn($query) => $query->latest()]
        ));

        return view('books.show', [ 'book' => $book]);
    }*/

    public function show(int $id)
    {
        $cacheKey = 'book:' . $id;

        $book = cache()->remember($cacheKey, 3600,
            fn() => Book::with([ 'reviews' => fn($query) => $query->latest() ])->withAvgRating()->withReviewsCount()->findOrFail($id));

        return view('books.show', [ 'book' => $book ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
