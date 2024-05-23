<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageDayTranslation extends Model
{
    use HasFactory;
    protected $fillable=[
        'package_day_id',
        'locale_id',
        'content'
    ];
    public function packageDay()
    {
        return $this->belongsTo(PackageDay::class);
    }
    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }
}
