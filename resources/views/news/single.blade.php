<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="panel-title"><a href="{{ $post->url }}">{{ $post->title }}</a></h2>

        <span class="text-tiny">
            Veröffentlicht am: {{ $post->published_at->format('d.m.Y') }}
            von {{ $post->author->name }}
        </span>

        <span class="text-tiny">
            @if (\Auth::check())
                <a href="{{ route('admin.news.edit', $post->id) }}">Eintrag bearbeiten</a>
            @endif
        </span>

        <div class="text-muted text-tiny">
            @unless ($post->comments->isEmpty())
                {{ $post->comments()->count() }} Reaktionen.
            @endunless

            <a href="{{ $post->url }}#comments">Reagieren.</a>
        </div>
    </div>
    <div class="content panel-body">
        <article>
            {!! $post->markdown_content !!}
        </article>
    </div>

    <div class="bottom-meta panel-footer">
        <ul class="list-unstyled list-inline text-tiny">
            @unless ($post->tags->isEmpty())
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
            @endunless

            @if ( $post->updated_at > $post->published_at)
                <li>{{ $post->updated_at->diffForHumans() }} zuletzt aktualisiert</li>
            @endif
        </ul>
    </div>
</div>
