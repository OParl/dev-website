@extends ('admin.base')

@section ('content')
    <div class="col-md-10 col-md-offset-1">
        @include ('admin.errors')

        <table class="table table-striped table-hover table-responsive">
            <thead>
                <tr>
                    {{-- TODO: Display order caret in headers --}}
                    <th><a href="?order_by=id">#</a></th>
                    <th><a href="?order_by=title">Titel</a></th>
                    <th><a href="?order_by=created_at">Erstellt</a></th>
                    <th><a href="?order_by=published_at">Veröffentlicht</a></th>
                    <th><a href="?order_by=author_id">Autor</a></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>
                            @if ($post->is_published && !$post->is_scheduled)
                                <a href="{{ $post->url }}">{{ $post->title }}</a>
                            @else
                                {{ $post->title }}
                            @endif

                            <ul class="list-unstyled list-inline text-tiny">
                                <li><a href="{{ route('admin.news.edit', $post->id) }}" class="text-info">Bearbeiten</a></li>
                                <li>
                                    <a data-title="{{ $post->title }}" data-href="{{ route('admin.news.delete', $post->id) }}"
                                       class="link-danger" data-toggle="modal" data-target="#textDeleteConfirmModal">Löschen</a>
                                </li>
                            </ul>
                        </td>
                        <td>{{ $post->created_at->format('d.m.Y H:i:s') }}</td>
                        <td>
                            {{ $post->is_published ? $post->published_at->format('d.m.Y H:i:s') : 'Entwurf' }}

                            @if ($post->is_scheduled)
                                <span class="text-muted">(Vorgeplant)</span>
                            @endif
                        </td>
                        <td>{{ $post->author->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-center">
            {!! $posts->render() !!}
        </div>
    </div>

    @include ('admin.news.delete')
@stop
