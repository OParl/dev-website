<nav class="toc hidden-sm" id="toc">
    <form class="form-inline" action="#" method="get">
        <div class="input-group">
            <div class="input-group-addon">
                <span aria-hidden="true" class="glyphicon glyphicon-search"></span>
            </div>
            <input type="search" class="form-control" placeholder="Suchen&hellip;" name="search" />
        </div>
    </form>

    <div>
        {!! $livecopy->getNav() !!}
        <div class="overlay" aria-hidden="true"></div>
    </div>
</nav>
