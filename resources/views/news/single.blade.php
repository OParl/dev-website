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

    @unless ($post->tags->isEmpty())
        <div class="bottom-meta panel-footer text-tiny">
            <ul class="list-inline list-unstyled">
                <li>Tags:</li>

                @foreach ($post->tags as $tag)
                    <li>
                        <a href="{{ route('news.tag', $tag->slug) }}">{{ $tag->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endunless
</div>
