<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locale extends Model
{
    use HasFactory;
    protected $fillable =[
        'code',
        'name'
    ];

    public function restaurantTranslation()
    {
        return $this->hasMany(RestaurantTranslation::class, 'local_id');
    }
    public function hotelTranslation()
    {
        return $this->hasMany(HotelTranslation::class, 'local_id');
    }
    public function cityTranslation()
    {
        return $this->hasMany(CityTranslation::class, 'local_id');
    }
    public function areaTranslations()
    {
        return $this->hasMany(AreaTranslations::class, 'local_id');
    }
    public function activityTranslations()
    {
        return $this->hasMany(ActivityTranslations::class, 'local_id');
    }
    public function apartmentTranslations()
    {
        return $this->hasMany(ApartmentTranslations::class, 'local_id');
    }
}
