<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['area_id', 'locale_id', 'name' , 'description'];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }
}
