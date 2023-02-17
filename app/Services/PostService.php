<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use LaravelIdea\Helper\App\Models\_IH_Post_C;

class PostService
{

    const EXCERPT_PREFIX = 40;
    const EXCERPT_LENGTH = 100;

    public function __construct(private Post $entity)
    {
    }

    public function search(string $search)
    {
        return $this->entity::query()
            ->with('user:id,name')
            ->whereFullText(['title', 'body'], $search)
            ->selectRaw("id, user_id, title, is_public, slug, created_at"
                . "SUBSTRING(body, GREATEST(1, LOCATE(?, body) - ?), ?) AS body_excerpt",
                [$search, self::EXCERPT_PREFIX, self::EXCERPT_LENGTH])
            ->get();
    }

    public function getAll(): array|Collection|_IH_Post_C
    {
        return $this->entity::all();
    }
}
