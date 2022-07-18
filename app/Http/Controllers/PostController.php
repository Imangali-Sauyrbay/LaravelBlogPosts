<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blogpost;
use App\Http\Requests\StorePostRequest;

class PostController extends Controller
{

    public function index()
    {
        return view('posts.index', ['posts' => Blogpost::orderBy('created_at', 'desc')->get()]);
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

        $request->session()->flash('status', 'new blogpost was created');


        return redirect()->route('posts.show', ['post' => $data['slug']]);
    }

    public function show(Blogpost $post)
    {
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
        $data = $request->validated();
        $data['slug'] = Blogpost::getSlug($data['title']);

        $post->fill($data);
        $post->save();

        $request->session()->flash('status', 'blogpost was updated');

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
