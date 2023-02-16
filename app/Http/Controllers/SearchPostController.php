<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use Illuminate\Http\Request;

class SearchPostController extends Controller
{
    public function __invoke(Request $request, PostService $postService)
    {
        $posts = $request->filled('search')
            ? $postService->search($request->input('search'))
            : $postService->getAll();

        return response()->json($posts);
    }
}
