@extends ('admin.base')

@section ('content')
    <div class="col-md-10 col-md-offset-1">
        <h2 id="post_title" data-is-published="{{ ($post->is_published) ? '1' : '0' }}">
            @if (ends_with(\Route::currentRouteName(), 'edit'))
                Eintrag “{{ $post->title }}” bearbeiten
            @else
                Neuen Eintrag erstellen
            @endif
        </h2>

        @include ('admin.errors')

        <form action="{{ route('admin.news.save') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            @if ($post->id > 0)
                <input type="hidden" name="id" value="{{ $post->id }}" />
            @endif

            <div class="form-group">
                <div class="col-md-3">
                    <label for="title">Titel</label>
                </div>

                <div class="col-md-9">
                    <input type="text" id="title" name="title" class="form-control" value="{{ $post->title }}">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-3">
                    <label for="slug">Slug</label>
                </div>

                <div class="col-md-9">
                    <input type="text" id="slug" name="slug" class="form-control" value="{{ $post->slug }}">
                </div>
            </div>

            <hr />

            <div class="form-group">
                <div class="col-md-3">
                    <label for="content">Inhalt</label>
                </div>
                <div class="col-md-9">
                    <textarea class="form-control" name="content" id="content"
                              rows="15">{{ $post->content }}</textarea>
                </div>
            </div>

            <hr />

            <div class="form-group">
                <div class="col-md-3">
                    <label for="tags">Tags</label>
                </div>
                <div class="col-md-9">
                    <select class="form-control"></select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-3">
                    <label for="published_at">Veröffentlichungsstatus</label>
                </div>

                <input type="hidden" name="published_at" id="published_at_input"
                       value="{{ ($post->is_published) ? $post->published_at->format(Carbon\Carbon::ISO8601) : '' }}">

                <div class="col-md-9">
                    <a id="published_at_control" data-toggle="collapse" href="#published_at_well"
                            aria-expanded="false" aria-controls="published_at_input">
                        <span>
                            @if ($post->is_published)
                                @if ($post->is_scheduled)
                                    Zur Veröffentlichung geplant am {{ $post->published_at->format('d.m.Y') }}
                                @else
                                    Veröffentlicht am {{ $post->published_at->format('d.m.Y') }}
                                @endif
                            @else
                                Entwurf
                            @endif
                        </span>
                        <span class="caret"></span>
                    </a>

                    <div class="collapse" id="published_at_well">
                        <div class="well">
                            <div id="published_at_user_input" data-date="{{ ($post->is_published) ? $post->published_at->format(Carbon\Carbon::ISO8601) : '' }}"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-3 col-md-offset-9 text-right">
                    @if ($post->id > 0)
                        <a data-title="{{ $post->title }}" data-href="{{ route('admin.news.delete', $post->id) }}"
                           class="btn btn-danger" data-toggle="modal" data-target="#textDeleteConfirmModal">Löschen</a>
                    @endif

                    <input type="submit" class="btn btn-primary" value="Speichern!">
                </div>
            </div>
        </form>
    </div>

    @include ('admin.news.delete')
@stop
