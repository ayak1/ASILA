<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraServiceTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'extra_service_id',
        'locale_id',
        'title',
        'main_description',
    ];
    public function extraService()
    {
        return $this->belongsTo(ExtraService::class,"extra_service_id");
    }
    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }

}
