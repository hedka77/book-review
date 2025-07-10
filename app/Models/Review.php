<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'review', 'rating'
        ]; //_Fillable es una propiedad de los modelos que permite especificar que algunas propiedades pueden ser asignadas masivamente

    public function book()
    {
        return $this->belongsTo(Book::class); //Define la inversa de hasMany del modelo de Books, indicando que cada review pertenece a 1 book
    }

    protected static function booted() //
    {
        static::updated(fn(Review $review) => cache()->forget('book:' . $review->book_id));
        static::deleted(fn(Review $review) => cache()->forget('book:' . $review->book_id));
        static::created(fn(Review $review) => cache()->forget('book:' . $review->book_id));
    }
}
