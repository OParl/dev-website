@extends ('base')

@section ('content')
    @forelse ($posts as $post)
        <div>
            <div class="meta">
                <h2>{{ $post->title }}</h2>

                <ul class="list-unstyled list-inline">
                    <li>Veröffentlicht am: {{ $post->published_at->format('d.m.Y') }}</li>
                    <li>von {{ $post->author->name }}</li>

                    @if ( $post->updated_at > $post->published_at)
                        <li>{{ $post->updated_at->diffForHumans() }} zuletzt aktualisiert</li>
                    @endif

                    @if ($post->tags()->count() > 0)
                        <li>
                            Tags:
                            <ul class="list-inline">
                                @foreach ($post->tags as $tag)
                                    <li>
                                        <a href="TAGURL-{{ $tag->slug }}">{{ $tag->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="content">
                {!! $post->content !!}
            </div>
        </div>
    @empty
        <div class="well well-lg">
            <p class="text-center">
                Leider sind keine Nachrichten für diese Anzeige vorhanden.
            </p>
        </div>
    @endforelse
@stop
