<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;
    protected $fillable = [
        'city_id',
        'rating',
        'opening_hours',
        'closing_hours',
    ];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function translations()
    {
        return $this->hasMany(RestaurantTranslation::class);
    }

}
