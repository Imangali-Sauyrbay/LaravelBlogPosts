<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaginatePostRequest;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\StorePostRequest;
use App\Models\Author;
use App\Models\Blogpost;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;


class PostController extends Controller
{
    public static $perPage = 10;

    public function __construct() {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(PaginatePostRequest $request)
    {

        if (!$request->withPagination(static::$perPage, Blogpost::query())) {
            return $request->path;
        }

        $posts = Cache::tags(['blogpost', 'posts'])->remember('page-' . $request->page,
        now()->addSeconds(5), fn() => Blogpost::latest()->withRelations()->offset($request->offset)->limit(static::$perPage)->get());

        return view('posts.index',
        [
            'posts' => $posts
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

        $post = Cache::tags(['blogpost'])->remember("blog-post-{$slug}", 60,
        function() use($slug) {
            return Blogpost::withRelations()->where('slug', '=', $slug)->first();
        });

        $sessionId = session()->getId();

        $counterKey = "blogpost-{$slug}-counter";
        $usersKey = "blogpost-{$slug}-users";

        $users = Cache::tags(['blogpost'])->get($usersKey, []);
        $usersUpdate = [];
        $diff = 0;
        $now = now();

        foreach ($users as $session => $lastVisit) {
            if($now->diffInMinutes($lastVisit) >= 1) {
                $diff--;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }

        if(!array_key_exists($sessionId, $users)
        || $now->diffInMinutes($users[$session]) >= 1) {
            $diff++;
        }

        $usersUpdate[$sessionId] = $now;

        Cache::tags(['blogpost'])->forever($usersKey, $usersUpdate);

        if(!Cache::has($counterKey)) {
            Cache::tags(['blogpost'])->forever($counterKey, 1);
        } else {
            Cache::tags(['blogpost'])->increment($counterKey, $diff);
        }

        $counter = Cache::tags(['blogpost'])->get($counterKey);

        abort_if(is_null($post), 404);
        return view('posts.show', ['post' => $post, 'counter' => $counter]);
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
