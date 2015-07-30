@forelse ($posts as $post)
    @include ('news.single', compact('post'))
@empty
    <div class="well well-lg">
        <p class="text-center">
            Leider sind keine Nachrichten für diese Anzeige vorhanden.
        </p>
    </div>
@endforelse

{!! $posts->render() !!}