<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'locale_id',
        'name',
        'short_description',
        'full_description'
    ];
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }
}
