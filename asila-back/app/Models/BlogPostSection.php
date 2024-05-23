<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPostSection extends Model
{
    use HasFactory;
    protected $fillable = [
        'blog_post_id',
        'image'
    ];
    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class);
    }
    public function translations()
    {
        return $this->hasMany(BlogPostSectionTranslation::class);
    }
}
