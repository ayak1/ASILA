<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;
    protected $fillable = [
        'apartment_type_id',
        'city_id',
        'longitude',
        'latitude',
        'is_for_sell',
        'is_for_rent',
        'space',
        'has_parking',
        'room_number',
        'baths_number',
        'parking_number',
        'pools_number',
        'floor',
        'is_rented',
        'is_sold',
        'available_for_rent_at',
        // 'rent_price',
        'sell_price',
        'in_installments',
        'sell_per_month',
        'rent_per_month',
    ];
    public function apartmentType()
    {
        return $this->belongsTo(ApartmentType::class);
    }
    public function translations()
    {
        return $this->hasMany(ApartmentTranslation::class, 'apartment_id', 'id');
    }
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
