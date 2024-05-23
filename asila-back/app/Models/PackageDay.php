<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageDay extends Model
{
    use HasFactory;
    protected $fillable = [
        'package_id',
        'day_number'
    ];

    public function package() {
        return $this->belongsTo(Package::class);
    }

    public function activities() {
        return $this->belongsToMany(Activity::class);
    }
    public function translations()
    {
        return $this->hasMany(PackageDayTranslation::class);
    }
}
