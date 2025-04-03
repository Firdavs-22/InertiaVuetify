<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;
use Inertia\Inertia;
use Inertia\Response;
use Inertia\ResponseFactory;

class PostController extends Controller
{
    public function index(): Response|ResponseFactory
    {
        return Inertia('Posts/Index', [
            "posts" => fn () =>  PostResource::collection(Post::with(['tags',"user"])->latest()->simplePaginate(9)),
            "tags" => Inertia::defer(function()  {;
                return Tag::all(['name']);
            }),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "title" => ['required', 'string', 'max:255'],
            "content" => ['required', 'string', 'max:1000'],
            "tags" => ['required', 'array', 'max:5', 'distinct', 'min:1'],
            "tags.*" => ['string', 'min:3', 'max:15'],
            "image" => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg',File::image()->max("10MB")],
        ]);

        $imageName = time() . '.' . Str::uuid();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $imageName.'.' . $image->getClientOriginalExtension();
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

        return redirect()->route("posts.index")->with("success", "Post created successfully");
    }
}
