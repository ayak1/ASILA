<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPostTranslation extends Model
{
    use HasFactory;
    protected $fillable = [
        'blog_post_id',
        'locale_id',
        'title',
        'title',
        'description',
        'text',
        'slug'
    ];
    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class);
    }
    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }
}
