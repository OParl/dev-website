<div class="panel panel-default panel-toc">
    <div class="panel-heading">
        <h3 class="panel-title">Inhaltsverzeichnis</h3>
    </div>
    <ul class="list-group">
        @foreach ($livecopy->getHeadlines() as $headline)
            <li class="list-group-item" data-level="{{ $headline->getLevel() }}">
                @if ($headline->getAnchor() !== '')
                    <a href="{{ $headline->getAnchor() }}">
                        {{ $headline->getText() }}
                    </a>
                @else
                    {{ $headline->getText() }}
                @endif
            </li>
        @endforeach
    </ul>
</div>
