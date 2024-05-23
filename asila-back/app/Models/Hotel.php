<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;
    protected $fillable = [
        'city_id',
        'has_parking',
        'has_free_breakfast',
        'has_swimming_pool',
        'has_spa',
        'has_fitness_center',
        'has_free_internet',
        'has_restaurant',
        'pets_allowed',
    ];
    public function translations()
    {
        return $this->hasMany(HotelTranslation::class);
    }
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
