<table class="table table-striped">
    <thead>
        <tr class="text-center">
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
            <td>
                {!! $build->linked_commit_message  !!}
                @if ($build->commit_message != $build->human_version)
                    <br />
                    <span>(Angezeigt als &quot;{{ $build->human_version  }}&quot;)</span>
                @endif
            </td>
            <td class="text-center">
                @if ($build->isAvailable)
                    <span class="glyphicon glyphicon-ok text-success"></span>
                @else
                    <span class="glyphicon glyphicon-remove text-danger"></span>
                @endif
            </td>
            <td>
                @include ('admin.specification.builds-table-options')
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5">Momentan sind keine weiteren Build-Informationen verfügbar.</td>
        </tr>
    @endforelse
    </tbody>
</table>