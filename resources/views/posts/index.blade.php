@extends('layouts.main')

@section('main')
    <div class="row">
        <div class="col-8">
            @each('posts.components.post', $posts, 'post', 'posts.components.empty')
        </div>
        <div class="col-4">
            <x-card-aside
                title="Most Commented Posts Of All Time!"
                subtitle="What people were talked about:">
                <x-slot:items>
                    @foreach ($most_commented_of_all_time as $post)
                        <li class="list-group-item">
                            <a href="{{ route('posts.show', ['post' => $post->slug]) }}" style="color: black; text-decoration: none;">
                                <p>{{ mb_strcut($post->title, 0, 60) . '...'}}</p>
                                <p>Comments ({{ $post->comments_count }})</p>
                            </a>
                        </li>
                    @endforeach
                </x-slot:items>
            </x-card-aside>

            <x-card-aside
                title="Most Active Authors Of All Time!"
                subtitle="What peoples were active:">
                <x-slot:items>
                    @foreach ($most_active_authors_of_all_time as $author)
                        <li class="list-group-item d-flex justify-content-between">
                            <p>{{ $author->name }}</p><span style="min-width: fit-content;">| Posts ({{ $author->blogposts_count }})</span>
                        </li>
                    @endforeach
                </x-slot:items>
            </x-card-aside>

            <x-card-aside
                title="Most Active Authors Of Last Month!"
                subtitle="What peoples were active:">
                <x-slot:items>
                    @foreach ($most_active_authors_of_last_month as $author)
                        <li class="list-group-item d-flex justify-content-between">
                            <p>{{ $author->name }}</p><span style="min-width: fit-content;">| Posts ({{ $author->blogposts_count }})</span>
                        </li>
                    @endforeach
                </x-slot:items>
            </x-card-aside>
        </div>
    </div>
@endsection
