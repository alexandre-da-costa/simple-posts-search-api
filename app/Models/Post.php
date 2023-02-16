<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'is_public',
    ];

    protected $guarded = [
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (self $post) {
            $post->slug = $post->generateUniqueSlug($post->title);
        });
    }

    private function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $existingPostsWithSameSlugCount = static::where('slug', 'like', $slug . '%')->count();

        return
            $existingPostsWithSameSlugCount > 0
                ? $slug . '-' . ($existingPostsWithSameSlugCount + 1)
                : $slug;
    }

    // Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
