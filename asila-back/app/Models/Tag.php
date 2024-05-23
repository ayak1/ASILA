<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    public function taggables()
    {
        return $this->morphToMany(Taggable::class, 'taggable');
    }
    public function translations()
    {
        return $this->hasMany(TagTranslation::class);
    }
}
