<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagTranslation extends Model
{
    use HasFactory;
    protected $fillable=[
        'locale_id',
        'tag_id',
        'name'
    ];
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }
}
