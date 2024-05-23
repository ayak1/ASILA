<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageContain extends Model
{
    use HasFactory;
    
    public function packages()
    {
        return $this->belongsToMany(Package::class);
    }
    public function translations()
    {
        return $this->hasMany(PackageContainTranslation::class);
    }

}
