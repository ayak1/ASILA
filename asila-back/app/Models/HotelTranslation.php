<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelTranslation extends Model
{
    use HasFactory;
    protected $fillable = [
        'hotel_id',
        'locale_id',
        'name',
        'address',
        'short_description',
        'full_description',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }

}
