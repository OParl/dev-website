@extends ('admin.base')

@section ('content')
    <div class="col-md-10 col-md-offset-1">
        @include ('admin.errors')

        <table class="table table-condensed table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Post</th>
                <th>Text</th>
                <th>Erstellt</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($comments as $comment)
                <tr>
                    <td>{{ $comment->id }}</td>
                    <td><a href="{{ route('admin.news.edit', $comment->post->id) }}">{{ $comment->post->title }}</a></td>
                    <td>
                        <div>
                            {{ $comment->content }}
                        </div>
                        <ul class="list-unstyled list-inline text-tiny">
                            <li><a href="{{ route('admin.comments.edit', $comment->id) }}">Bearbeiten</a></li>
                            <li>
                                <a data-href="{{ route('admin.comments.delete', $comment->id) }}"
                                   data-title="{{ $comment->post->title }}" data-author="{{ $comment->author_name }}"
                                   data-toggle="modal" data-target="#commentDeleteConfirmModal"
                                   class="link-danger">Löschen</a>
                            </li>
                        </ul>
                    </td>
                    <td>{{ $comment->created_at->diffForHumans() }}</td>
                    <td>
                        @include ('admin.comments.buttons')
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {!! $comments->render() !!}
    </div>

    @include ('admin.comments.delete')
@stop
