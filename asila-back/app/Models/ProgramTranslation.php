<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramTranslation extends Model
{
    use HasFactory;
    protected $fillable = [
        'program_id',
        'locale_id',
        'city_id',
        'title',
        'short_description',
        'full_description',
    ];
    public function program()
    {
        return $this->belongsTo(Program::class);
    }
    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }
}
