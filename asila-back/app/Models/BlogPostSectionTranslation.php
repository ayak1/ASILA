<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPostSectionTranslation extends Model
{
    use HasFactory;
    protected $fillable = [
        'blog_post_section_id',
        'locale_id',
        'title',
        'title',
        'description',
        'text',
    ];
    public function blogPostSection()
    {
        return $this->belongsTo(blogPostSection::class);
    }
    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }
}
