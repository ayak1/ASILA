<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationTypeTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['name','destination_type_id','locale_id'];

    public function destinationType()
    {
        return $this->belongsTo(DestinationType::class);
    }
    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }
}
