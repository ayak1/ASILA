<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $fillable = [
        'city_id',
        'is_popular'
    ];
    public function translations()
    {
        return $this->hasMany(AreaTranslation::class);
    }
    public function programs()
    {
        return $this->belongsToMany(Program::class);
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
