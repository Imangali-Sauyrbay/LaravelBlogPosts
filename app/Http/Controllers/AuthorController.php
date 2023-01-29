<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class AuthorController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('show');
        // $this->authorizeResource(Author::class, 'author');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        return view('authors.show', ['author' => $author]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function edit(Author $author)
    {
        $this->authorize('update', $author);
        return view('authors.edit', ['author' => $author]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        $this->authorize('update', $author);
        $request->validated();

        if($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars');

            if(isset($author->image)) {
                Storage::delete($author->image->path);
                $author->image->delete();
            }

            $author->image()->save(Image::make(['path' => $path]));
        }

        return redirect()
            ->back()
            ->withStatus('Profile image was updated!');
    }

}
