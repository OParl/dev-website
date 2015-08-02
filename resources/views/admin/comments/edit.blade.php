@extends ('admin.base')

@section ('content')
    <div class="col-md-10 col-md-offset-1">
        <h2>
            Kommentar zu “{{ $comment->post->title }}” vom
            {{ $comment->created_at->format('d.m.Y') }} bearbeiten
        </h2>

        @include ('admin.errors')

        <form action="{{ route('admin.comments.save') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            <input type="hidden" name="id" value="{{ $comment->id }}" />

            @if (!is_null($comment->author_id))
                <div class="form-group">
                    <div class="col-sm-3">
                        <label>Name <span class="text-muted">(Administrator)</span></label>
                    </div>
                    <div class="col-sm-9">
                        <p class="form-control-static">{{ $comment->author_name }}</p>
                        <input type="hidden" name="author_id" value="{{ $comment->author->id }}" />
                    </div>
                </div>
            @else
                <div class="form-group">
                    <div class="col-md-3">
                        <label for="author_name">Name</label>
                    </div>

                    <div class="col-md-9">
                        <input type="text" id="author_name" name="author_name" class="form-control" value="{{ $comment->author_name }}">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-3">
                        <label for="author_email">E-Mail-Adresse</label>
                    </div>

                    <div class="col-md-9">
                        <input type="text" id="author_email" name="author_email" class="form-control" value="{{ $comment->author_email }}">
                    </div>
                </div>
            @endif

            <hr />

            <div class="form-group">
                <div class="col-md-3">
                    <label for="content">Inhalt</label>
                </div>
                <div class="col-md-9">
                    <textarea class="form-control" name="content" id="content"
                              rows="15">{{ $comment->content }}</textarea>
                </div>
            </div>

            <hr />

            <div class="form-group">
                <div class="col-md-3">
                    <label for="status">Veröffentlichungsstatus</label>
                </div>

                <div class="col-md-9">
                    {{-- TODO: Radio Group --}}
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-3 col-md-offset-9 text-right">
                    <a data-href="{{ route('admin.comments.delete', $comment->id) }}"
                       data-title="{{ $comment->post->title }}" data-author="{{ $comment->author_name }}"
                       data-toggle="modal" data-target="#commentDeleteConfirmModal"
                       class="btn btn-danger">Löschen</a>

                    <input type="submit" class="btn btn-primary" value="Speichern!">
                </div>
            </div>
        </form>
    </div>

    @include ('admin.comments.delete')
@stop
