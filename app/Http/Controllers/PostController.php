<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blogpost;
use App\Http\Requests\StorePostRequest;
use DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $post = Blogpost::with(['comments', 'comments.author'])->orderBy('created_at', 'desc')->get();
        return view('posts.index', ['posts' => $post]);
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
        $data['slug'] = Blogpost::getSlug($data['title']);
        Blogpost::create($data);

        Session::flash('status', 'new blogpost was created');

        return redirect()->route('posts.show', ['post' => $data['slug']]);
    }

    public function show($slug)
    {
        $post = Blogpost::with(['comments', 'comments.author'])->where('slug', '=', $slug)->first();
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

        $data = $request->validated();
        $data['slug'] = Blogpost::getSlug($data['title']);

        $post->fill($data);
        $post->save();

        Session::flash('status', 'blogpost was updated');

        return redirect()->route('posts.show', ['post' => $data['slug']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blogpost $post)
    {
        $post->deleteOrFail();
        return redirect()->route('posts.index')->with('status', 'deleted successfully!');
    }
}
