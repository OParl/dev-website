@extends ('base')

@section ('content')
    @include ('news.single')

    @foreach ($post->comments as $comment)
        <div class="media">
            <div class="media-left">
                <a href="#">
                    <img class="media-object" src="..." alt="...">
                </a>
            </div>
            <div class="media-body">
                ...
            </div>
        </div>
    @endforeach
@stop
