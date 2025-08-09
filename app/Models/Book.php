<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = ['title', 'isbn', 'stock'];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
