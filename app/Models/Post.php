<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use Searchable;
    
    use HasFactory;

    protected $guarded = [];

    protected $fillable = ['title', 'slug', 'content', 'keywords','category_id','thumbnail', 'description', 'published'];

    protected $cast = [
        'published' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }

    public function setSlugAttribute($value)
    {
        $slug = $value;

        if(empty($slug)) {
            $slug = $this->attributes['title'];
        }

        $this->attributes['slug'] = Str::slug($slug, '-') . '-' . Str::random(10);
    }

    public function shouldBeSearchable()
    {
        return $this->published;
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        $array['category'] = $this->category['name'];
        $array['tag'] = array_column($this->tags()->get()->toArray(), 'name');

        return $array;
    }
}
