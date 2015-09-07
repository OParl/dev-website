<nav class="toc hidden-sm" id="toc">
    <form class="form-inline" action="#" method="get">
        <div class="input-group">
            <div class="input-group-addon">
                <span aria-hidden="true" class="glyphicon glyphicon-search"></span>
            </div>
            <input type="search" class="form-control" placeholder="Im Inhalt suchen&hellip;" name="search" />
        </div>
    </form>

    <div>
        {!! $livecopy->getNav() !!}
    </div>

    <footer class="text-muted">
        <small>
            Zuletzt aktualisiert {{ $livecopy->getLastModified()->diffForHumans() }}
        </small>
    </footer>
</nav>
