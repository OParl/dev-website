<nav class="toc" id="toc">
    @include ('downloads.button', ['buttonSize' => 'sm'])

    {{--
    <form class="form-inline" action="#" method="get">
        <div class="input-group">
            <div class="input-group-addon">
                <span aria-hidden="true" class="glyphicon glyphicon-search"></span>
            </div>
            <input type="search" class="form-control" placeholder="Inhaltsverzeichnis filtern&hellip;" name="search"
                   aria-label="Mit diesem Formular lässt sich das Inhaltsverzeichnis nach Begriffen filtern" />
        </div>
    </form>
    --}}

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
