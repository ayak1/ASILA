<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantTranslation extends Model
{
    use HasFactory;
    protected $fillable = [
        'restaurant_id',
        'locale_id',
        'name',
        'address',
        'short_description',
        'full_description',
    ];
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }
}
