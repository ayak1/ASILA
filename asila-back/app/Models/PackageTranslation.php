<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageTranslation extends Model
{
    use HasFactory;
    protected $fillable = [
        'package_id',
        'locale_id',
        'title',
        'short_description',
        'full_description',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }
}
