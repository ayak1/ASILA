<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $fillable = [
        'city_id',
        'area_id',
        'destination_type_id'
    ];

    public function translations()
    {
        return $this->hasMany(ActivityTranslation::class);
    }
    public function programs()
    {
        return $this->belongsToMany(Program::class);
    }
    public function packageDays()
    {
        return $this->belongsToMany(PackageDay::class);
    }
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
    public function destinationType()
    {
        return $this->belongsTo(DestinationType::class);
    }
}
