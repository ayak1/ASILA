<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageContainTranslation extends Model
{
    use HasFactory;
    protected $fillable = [
        'package_contain_id',
        'locale_id',
        'content'
    ];
    public function packageContain()
    {
        return $this->belongsTo(PackageContain::class);
    }
    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }

}
