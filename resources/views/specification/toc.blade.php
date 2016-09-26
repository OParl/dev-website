<nav class="toc" id="toc">
    @include ('downloads.button', ['buttonSize' => 'sm'])
    <div>
        {!! $liveversion->getNav() !!}
    </div>

    <footer class="text-muted">
        <small>
            <a href="//github.com/OParl/spec/commit/{{ $liveversion->getHash() }}"> Zuletzt aktualisiert
            {{ $liveversion->getLastModified()->diffForHumans() }}
            </a>
        </small>
    </footer>
</nav>
