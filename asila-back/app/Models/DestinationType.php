<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationType extends Model
{
    use HasFactory;

    public function translations()
    {
        return $this->hasMany(DestinationTypeTranslation::class);
    }
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
