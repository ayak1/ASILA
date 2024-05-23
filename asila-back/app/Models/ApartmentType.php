<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApartmentType extends Model
{
    use HasFactory;

    public function apartments(){
        return $this->hasMany(Apartment::class);
    }
    public function translations()
    {
        return $this->hasMany(ApartmentTypeTranslation::class);
    }
}
