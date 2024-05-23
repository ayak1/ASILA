<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraServiceSection extends Model
{
    use HasFactory;
    protected $fillable=[
        'extra_service_id'
    ];
    public function translations()
    {
        return $this->hasMany(ExtraServiceSectionTranslation::class,'e_service_section_id');
    }
    public function extraService()
    {
        return $this->belongsTo(ExtraService::class,'extra_service_id');
    }
}
