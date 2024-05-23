<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraServiceSectionTranslation extends Model
{
    use HasFactory;
    protected $fillable = [
        'e_service_section_id',
        'locale_id',
        'section_title',
        'section_description',
        'list_of_adv'
    ];
    protected $casts = [
        'list_of_adv' => 'array',
    ];
    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }
    public function extraServiceSection()
    {
        return $this->belongsTo(ExtraServiceSection::class,"e_service_section_id");
    }
}
