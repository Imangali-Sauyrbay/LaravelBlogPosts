<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\StoreCommentRequest;

class AuthorCommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only('store');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Author $author, StoreCommentRequest $request)
    {
        $author->commentsOn()->create([
            'content' => $request->input('comment'),
            'author_id' => $request->user()->id,
        ]);

        return redirect()->back()->withStatus('Comment was added!');
    }
}
