<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApartmentTranslation extends Model
{
    use HasFactory;
    protected $fillable = [
        'apartment_id',
        'locale_id',
        'address',
        'description',
        'title',
        'slug',

    ];
    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }
}
