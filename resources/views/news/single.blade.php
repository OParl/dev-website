<div>
    <div class="meta">
        <h2><a href="{{ $post->url }}">{{ $post->title }}</a></h2>

        <ul class="list-unstyled list-inline text-tiny">
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

            @if (\Auth::check())
                <li>
                    <a href="{{ route('admin.news.edit', $post->id) }}">Eintrag bearbeiten</a>
                </li>
            @endif
        </ul>
    </div>
    <div class="content">
        {!! $post->markdown_content !!}
    </div>
</div>
