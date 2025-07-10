<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Book $book)
    {
        $hashKey     = $this->getRateLimiterKey('reviews', $request->ip());
        $attempts    = RateLimiter::attempts($hashKey);
        $retriesLeft = RateLimiter::retriesLeft($hashKey, 10);

        return view('books.reviews.create', [ 'book' => $book ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Book $book)
    {
        $data = $request->validate([
                                       'review' => 'required|min:15', 'rating' => 'required|min:1|max:5|integer'
                                   ]);

        $book->reviews()->create($data);

        return redirect()->route('books.show', [ 'book' => $book ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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


    function getRateLimiterKey($name, $key): string
    {
        // Hash the IP address using the same method Laravel uses internally
        //return 'rate_limiter:' . sha1($name . '|' . $key);
        return md5($name . $key);
    }

    function getRateLimiterAttempts($limiter, $key)
    {
        var_dump($limiter, $key);
        // Create a unique key based on the limiter name and the given key
        $hashedKey   = Str::slug($limiter, '-') . '|' . $key;
        $rateLimiter = new RateLimiter(Cache::store(Config::get('cache.default')));

        return $rateLimiter::attempts($hashedKey);
    }
}
