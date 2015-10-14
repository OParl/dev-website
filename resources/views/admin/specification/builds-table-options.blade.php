<ul class="list-unstyled nowrap list-inline">
    <li>
        <a href="{{ route('admin.specification.edit', $build->id) }}" class="btn btn-sm btn-primary">
            <span class="glyphicon glyphicon-edit"></span>
        </a>
    </li>
    <li>
        @if ($build->displayed)
            <a href="#" class="btn btn-sm btn-default">
                <span class="glyphicon glyphicon-eye-close" title="Verstecken"></span>
            </a>
        @else
            <a href="#" class="btn btn-sm btn-default">
                <span class="glyphicon glyphicon-eye-open" title="Anzeigen"></span>
            </a>
        @endif
    </li>
    <li>
        @if ($build->is_available)
            <a href="{{ route('admin.specification.delete', $build->hash) }}" class="btn btn-sm btn-danger">
                <span class="glyphicon glyphicon-trash" title="Löschen"></span>
            </a>
        @else
            <a href="{{ route('admin.specification.fetch', $build->hash) }}" class="btn btn-sm btn-default">
                <span class="glyphicon glyphicon-download-alt" title="Bereitstellen"></span>
            </a>
        @endif
    </li>
</ul>
