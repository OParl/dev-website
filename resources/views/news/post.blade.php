@extends ('base')

@section ('content')
    @include ('news.single')

    @if (isset($info))
        <div class="alert alert-success">
            {{ $info }}
        </div>
    @endif

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><a name="comments">Kommentare</a></h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ $post->url }}">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $post->id }}" />

                <div class="form-group">
                    <div class="col-md-3">
                        <label for="name">Name:</label>
                    </div>
                    <div class="col-md-9">
                        @if (\Auth::check())
                            <p class="form-control-static">{{ \Auth::user()->name }}</p>
                        @else
                            <input type="text" name="name" id="name" maxlength="255" class="form-control" />
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="email">E-Mail-Adresse:</label>
                    </div>
                    <div class="col-md-9">
                        @if (\Auth::check())
                            <p class="form-control-static">{{ \Auth::user()->email }}</p>
                        @else
                            <input type="email" name="email" id="email" maxlength="255" class="form-control" />
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="content">Kommentar:</label>
                    </div>
                    <div class="col-md-9">
                        <textarea class="form-control" rows="10" name="content" id="content"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-2 col-md-offset-10">
                        <input type="submit" value="Abschicken!" class="form-control" />
                    </div>
                </div>
            </form>

            @foreach ($post->comments as $comment)
                <div class="media">
                    <div class="media-left">
                        <img class="media-object img-circle" src="{{ $comment->gravatar }}" />
                        <br />
                        <span class="text-muted">{{ $comment->author_name }}</span>
                    </div>
                    <div class="media-body">
                        {{ $comment->content }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop
