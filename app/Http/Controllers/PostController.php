<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Http\Requests\StorePostRequest;
use App\Models\Author;
use App\Models\Blogpost;

class PostController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        return view('posts.index',
        [
            'posts' => Blogpost::latest()->withCount('comments')->get(),
            'most_commented_of_all_time' => Blogpost::mostCommented()->take(5)->get(),
            'most_active_authors_of_all_time' => Author::withMostBlogposts()->take(5)->get(),
            'most_active_authors_of_last_month' => Author::withMostBlogpostsLastMonth()->take(5)->get()
        ]);
    }

    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['author_id'] = auth()->user()->id;

        $post = Blogpost::create($data);

        Session::flash('status', 'new blogpost was created');

        return redirect()->route('posts.show', ['post' => $post['slug']]);
    }

    public function show($slug)
    {
        $post = Blogpost::with(['comments', 'comments.author'])->where('slug', '=', $slug)->first();
        abort_if(is_null($post), 404);
        return view('posts.show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Blogpost $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePostRequest $request, Blogpost $post)
    {
        $this->authorize('update', $post);

        $post->fill($request->validated())->saveOrFail();

        Session::flash('status', 'blogpost was updated');

        return redirect()->route('posts.show', ['post' => $post['slug']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blogpost $post)
    {
        $this->authorize('delete', $post);
        $post->deleteOrFail();
        return redirect()->route('posts.index')->with('status', 'deleted successfully!');
    }
}
