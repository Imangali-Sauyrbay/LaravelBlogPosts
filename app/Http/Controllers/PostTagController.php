<?php

namespace App\Http\Controllers;

use App\Models\Blogpost;
use Illuminate\Http\Request;
use App\Http\Requests\PaginatePostRequest;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;

class PostTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PaginatePostRequest $request,string $slug)
    {
        if (!$request->withPagination(PostController::$perPage,
        Blogpost::whereHas('tags', function($query) use ($slug) {$query->where('tags.slug', $slug);}), "-tag-$slug")) {
            return $request->path;
        }

        $posts = Cache::tags(['blogpost', 'posts', 'tags'])->remember('tag-' . $slug . '-page-' . $request->page,
        now()->addSeconds(10), fn() => Tag::where('slug', $slug)->first()
        ->blogposts()
        ->withRelations()
        ->latest()
        ->withCount('comments')
        ->limit(PostController::$perPage)
        ->offset($request->offset)->get());

        return view('posts.index',
        [
            'posts' => $posts,
            'tag_name' => Cache::tags(['blogpost', 'posts', 'tags'])->remember('tag-' . $slug . '-name', now()->addMinute(), fn() => Tag::where('slug', $slug)->first()->name)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
