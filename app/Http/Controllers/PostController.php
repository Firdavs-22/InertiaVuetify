<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;
use Inertia\Inertia;
use Inertia\Response;
use Inertia\ResponseFactory;

class PostController extends Controller
{
    public function infiniteScroll(): Response|ResponseFactory
    {
        return Inertia('Posts/InfiniteScroll', [
            "posts" => PostResource::collection(Post::with(['tags', "user"])
                ->latest()->paginate(9)),
            "tags" => Inertia::defer(function () {
                return Tag::all(['name']);
            }),
            "newPost" => null
        ]);
    }

    public function store(Request $request): Response|ResponseFactory|RedirectResponse
    {
        $validated = $request->validate([
            "title" => ['required', 'string', 'max:255'],
            "content" => ['required', 'string', 'max:1000'],
            "tags" => ['required', 'array', 'max:5', 'distinct', 'min:1'],
            "tags.*" => ['string', 'min:3', 'max:15'],
            "image" => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', File::image()->max("10MB")],
        ]);

        $imageName = time() . '.' . Str::uuid();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $imageName . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        } else {
            return back()->withErrors(["image" => "Invalid image"])->onlyInput("image");
        }

        $post = Post::create([
            "title" => $validated['title'],
            "content" => $validated['content'],
            "user_id" => auth()->user()->id,
            "image" => $imageName,
            "is_published" => true,
            "published_at" => now(),
        ]);

        $tagsNames = $validated['tags'];
        $data = collect($tagsNames)->map(function ($name) {
            return ["name" => trim($name)];
        })->toArray();

        Tag::upsert($data, ['name']);

        $tagIds = Tag::whereIn('name', $tagsNames)->pluck("id")->toArray();

        $post->tags()->sync($tagIds);
        $post->load(['tags', 'user']);

        return Inertia('Posts/InfiniteScroll', [
            "newPost" => PostResource::make($post),
            "posts" => [],
            "tags" => [],
        ])->with("success", "Post created successfully");
    }

    public function index(Request $request): Response|ResponseFactory
    {
        $validSortColumns = ['title', 'published_at'];
        $sortBy = in_array($request->input('sort_by'), $validSortColumns)
            ? $request->input('sort_by') : "published_at";

        $sortDirection = in_array(strtolower($request->input('sort_direction')), ['asc', 'desc'])
            ? strtolower($request->input('sort_direction')) : "desc";

        $search = $request->input('search', '');

        $postQuery = Post::query(['tags', 'user'])->orderBy($sortBy, $sortDirection);

        if (!empty($search)) {
            $keywords = array_filter(array_map('trim', explode(' ', $search)));
            if (!empty($keywords)) {
                $postQuery->where(function ($query) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $query->orWhere('title', 'like', "%$keyword%")
                            ->orWhere('content', 'like', "%$keyword%");
                    }
                });
            }
        }


        $posts = $postQuery->paginate(10);

        return Inertia("Posts/Index", [
            "posts" => PostResource::collection($posts->items()),
            "pagination" => [
                "currentPage" => $posts->currentPage(),
                "lastPage" => $posts->lastPage(),
            ],
            "sort" => [
                "by" => $sortBy,
                "direction" => $sortDirection,
            ],
            "search" => $search,
            "currentPath" => $request->path(),
        ]);
    }

    public function create(): Response|ResponseFactory
    {
        return Inertia("Posts/Create", [
            "tags" => fn() => Tag::all(['name'])->map(fn ($tag) => $tag->name),
        ]);
    }
}
