<table class="table">
    <thead>
    <tr>
        <th>Hash</th>
        <th>Erstellt am</th>
        <th>Beschreibung</th>
        <th>Verfügbarkeit</th>
        <th>Optionen</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($builds as $build)
        <tr>
            <td>
                <a href="//github.com/OParl/spec/commits/{{ $build->hash }}">
                    {{ $build->short_hash }}
                </a>
            </td>
            <td>{{ $build->created_at->formatLocalized('%d.%m.%Y') }}</td>
            <td>{!! $build->linked_commit_message  !!}</td>
            <td>
                @if ($build->isAvailable)
                    <span class="glyphicon glyphicon-ok text-success"></span>
                @else
                    <span class="glyphicon glyphicon-remove text-danger"></span>
                @endif
            </td>
            <td>
                <ul class="list-inline">
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
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5">Momentan sind keine weiteren Build-Informationen verfügbar.</td>
        </tr>
    @endforelse
    </tbody>
</table>