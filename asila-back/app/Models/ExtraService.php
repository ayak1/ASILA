<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraService extends Model
{
    use HasFactory;
    protected $fillable = ['slug'];
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function translations()
    {
        return $this->hasMany(ExtraServiceTranslation::class);
    }
    public function sections()
    {
        return $this->hasMany(ExtraServiceSection::class);
    }
}
