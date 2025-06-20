<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Book extends Model
{
    use HasFactory;

    public function reviews()
    {
        return $this->hasMany(Review::class); //Define la relación de 1:N de un libro a muchos reviews
    }

    public function scopeTitle(Builder $query, string $title): Builder
    {
        return $query->where('title', 'LIKE', '%' . $title . '%');
    }

    public function scopeCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', '=',  $category);
    }

    public function scopeWithReviewsCount(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withCount([ 'reviews' => fn(Builder $q) => $this->dateRangeBuilder($q, $from, $to) ])
                     ->orderBy('reviews_count', 'DESC');
    }

    public function scopeWithAvgRating(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withAvg([ 'reviews' => fn(Builder $q) => $this->dateRangeBuilder($q, $from, $to) ], 'rating');
    }

    public function scopePopular(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withReviewsCount($from, $to)->orderBy('reviews_count', 'DESC');
    }

    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withAvgRating($from, $to)->orderBy('reviews_avg_rating', 'DESC');
    }

    public function scopeMinReviews(Builder $query, int $minReviews): Builder|QueryBuilder
    {
        return $query->having('reviews_count', '>=', $minReviews);
    }


    private function dateRangeBuilder(Builder $query, $from = null, $to = null): void
    {
        if ($from && !$to)
        {
            $query->where('created_at', '>=', $from);
        }
        elseif (!$from && $to)
        {
            $query->where('created_at', '<=', $to);
        }
        elseif ($from && $to)
        {
            $query->whereBetween('created_at', [ $from, $to ]);
        }
    }


    public function scopePopularLastMonth(Builder $query): Builder|QueryBuilder
    {
        return $query->popular(now()->subMonth(), now())->highestRated(now()->subMonth(), now())->minReviews(2);
    }

    public function scopePopularLast6Months(Builder $query): Builder|QueryBuilder
    {
        return $query->popular(now()->subMonths(6), now())->highestRated(now()->subMonths(6), now())->minReviews(5);
    }

    public function scopeHighestRatedLastMonth(Builder $query): Builder|QueryBuilder
    {
        return $query->highestRated(now()->subMonth(), now())->popular(now()->subMonth(), now())->minReviews(2);
    }

    public function scopeHighestRatedLast6Months(Builder $query): Builder|QueryBuilder
    {
        return $query->highestRated(now()->subMonths(6), now())->popular(now()->subMonths(6), now())->minReviews(5);
    }

    protected static function booted()
    {
        static::updated(fn(Book $book) => cache()->forget('book:' . $book->id));
        static::deleted(fn(Book $book) => cache()->forget('book:' . $book->id));
    }
}
