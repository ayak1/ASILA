<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['city_id', 'locale_id', 'name' , 'description'];
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }
}
