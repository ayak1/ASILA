<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    protected $fillable = [
        'city_id',
        'group_program',
        'private_car_program',
        'destination_type_id'
    ];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function translations()
    {
        return $this->hasMany(ProgramTranslation::class);
    }

    public function areas()
    {
        return $this->belongsToMany(Area::class);
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
    
}
