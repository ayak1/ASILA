<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;
    protected $fillable = [
        'image'
    ];
    public function sections()
    {
        return $this->hasMany(Section::class);
    }
    public function translations()
    {
        return $this->hasMany(BlogPostTranslation::class);
    }
}
