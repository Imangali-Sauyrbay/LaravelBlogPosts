<?php

namespace App\Http\Controllers;

use App\Models\Blogpost;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Traits\PaginationTrait;
use Illuminate\Support\Facades\Cache;

class PostTagController extends Controller
{
    use PaginationTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(string $slug)
    {
        $countQuery = Blogpost::whereHas('tags', function($query) use ($slug) {
            $query->where('tags.slug', $slug);
        });

        if($redirect = $this->paginate($countQuery, ['blogpost', 'posts-count', 'tag'], "-tag-$slug"))
        {
            return $redirect;
        }


        $posts = Cache::tags(['blogpost', 'posts', 'tags'])->remember('tag-' . $slug . '-page-' . $this->page,
        now()->addSeconds(10), fn() => $this->getPaginatedRecords(
            Tag::where('slug', $slug)
            ->first()
            ->blogposts()
            ->withRelCommCountLatest()
            ->getQuery()
        ));

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
