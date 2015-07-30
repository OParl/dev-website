@extends ('admin.base')

@section ('content')
    <div class="col-md-10 col-md-offset-1">
        @include ('admin.errors')

        <table class="table table-striped table-hover table-responsive">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Titel</th>
                    <th>Erstellt</th>
                    <th>Veröffentlicht</th>
                    <th>Autor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>
                            <a href="{{ $post->url }}">{{ $post->title }}</a>
                            <ul class="list-unstyled list-inline text-tiny">
                                <li><a href="{{ route('admin.news.edit', $post->id) }}" class="text-info">Bearbeiten</a></li>
                                <li>
                                    <a data-title="{{ $post->title }}" data-href="{{ route('admin.news.delete', $post->id) }}"
                                       class="text-danger" data-toggle="modal" data-target="#textDeleteConfirmModal">Löschen</a>
                                </li>
                            </ul>
                        </td>
                        <td>{{ $post->created_at->format('d.m.Y H:i:s') }}</td>
                        <td>{{ $post->is_published ? $post->published_at->format('d.m.Y H:i:s') : 'Entwurf' }}</td>
                        <td>{{ $post->author->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="textDeleteConfirmModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Abbrechen"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Wirklich löschen?</h4>
                </div>
                <div class="modal-body">Soll “<span class="text-title"></span>” wirklich gelöscht werden?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                    <a href="" class="btn btn-danger">Löschen</a>
                </div>
            </div>
        </div>
    </div>
@stop
