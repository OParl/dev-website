@forelse ($posts as $post)
    @include ('news.single')
@empty
    <div class="well well-lg">
        <p class="text-center">
            Leider sind keine Nachrichten für diese Anzeige vorhanden.
        </p>
    </div>
@endforelse

@if ($posts->count() > 0)
    <div class="text-center">
        {!! $posts->render() !!}
    </div>
@endif
