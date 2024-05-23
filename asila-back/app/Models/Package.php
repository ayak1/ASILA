<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'can_choose_hotel',
        'duration',
        'price'
    ];
    public function translations()
    {
        return $this->hasMany(PackageTranslation::class);
    }
    public function packageDays()
    {
        return $this->hasMany(PackageDay::class);
    }
    public function packageContains()
    {
        return $this->belongsToMany(PackageContain::class);
    }
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
    public function keywords()
    {
        return $this->morphMany(Keyword::class, 'keywordable');
    }
}
