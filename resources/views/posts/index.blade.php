@extends('layouts.main')

@section('main')
    <div class="row">
        <div class="col-8">
            @each('posts.components.post', $posts, 'post', 'posts.components.empty')
        </div>
        <div class="col-4">
            <div class="card" style="width:18rem">
                <div class="card-body">
                    <h4 class="card-title">Most Commented Posts Of All Time!</h4>
                    <div class="card-text">What people were talked about:</div>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($most_commented_of_all_time as $post)
                        <li class="list-group-item">
                            <a href="{{ route('posts.show', ['post' => $post->slug]) }}" style="color: black; text-decoration: none;">
                                <p>{{ mb_strcut($post->title, 0, 60) . '...'}}</p>
                                <p>Comments ({{ $post->comments_count }})</p>
                            </a>
                        </li>
                    @empty
                        <li class="list-group-item">
                            It seems there is no post...
                        </li>
                    @endforelse
                </ul>
            </div>

            <div class="card mt-3" style="width:18rem">
                <div class="card-body">
                    <h4 class="card-title">Most Active Authors Of All Time!</h4>
                    <div class="card-text">What peoples were active:</div>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($most_active_authors_of_all_time as $author)
                        <li class="list-group-item d-flex justify-content-between">
                            <p>{{ $author->name }}</p><span style="min-width: fit-content;">| Posts ({{ $author->blogposts_count }})</span>
                        </li>
                    @empty
                        <li class="list-group-item">
                            It seems there is no one...
                        </li>
                    @endforelse
                </ul>
            </div>

            <div class="card mt-3" style="width:18rem">
                <div class="card-body">
                    <h4 class="card-title">Most Active Authors Of Last Month!</h4>
                    <div class="card-text">What peoples were active:</div>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($most_active_authors_of_last_month as $author)
                        <li class="list-group-item d-flex justify-content-between">
                            <p>{{ $author->name }}</p><span style="min-width: fit-content;">| Posts ({{ $author->blogposts_count }})</span>
                        </li>
                    @empty
                        <li class="list-group-item">
                            It seems there is no one...
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
