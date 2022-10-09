<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 * @mixin IdeHelperPost
 */
class Post extends Model
{
    use HasFactory, Sluggable;

    protected $guarded = ['id'];
    protected $with = ['author', 'category'];

    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['search'] ?? false, function (Builder $query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('body', 'like', '%' . $search . '%');
        });

        $query->when($filters['category'] ?? false, function (Builder $query, $category) {
            return $query->whereHas('category', function (Builder $query) use ($category) { // what the hell is this brow
                $query->where('slug', $category);
            });
        });

        $query->when($filters['author'] ?? false, function (Builder $query, $author) {
            return $query->whereHas('author', fn(Builder $query) => // don't need that 'use' keyword, arrow function FTW
                $query->where('username', $author)
            );
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return "slug";
    }

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
