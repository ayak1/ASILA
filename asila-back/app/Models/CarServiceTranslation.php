<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarServiceTranslation extends Model
{
    use HasFactory;
    protected $fillable = [
        'car_service_id',
        'locale_id',
        'title',
        'description'
    ];
    public function CarService()
    {
        return $this->belongsTo(CarService::class);
    }
    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }
}
