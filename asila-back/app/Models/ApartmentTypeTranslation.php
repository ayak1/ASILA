<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApartmentTypeTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['type'];

    public function apartmentType()
    {
        return $this->belongsTo(ApartmentType::class, 'apartment_type_id', 'id');
    }
    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }
}
