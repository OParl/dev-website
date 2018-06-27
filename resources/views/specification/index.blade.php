@extends ('base')

@section ('subheader')
    <div class="level-item">
        <a href="{{ route('downloads.index') }}">@lang('app.downloads.title')</a>
    </div>
@stop

@section ('content')
    <div class="columns">
        <aside class="column is-one-third">
            <div class="box table-of-contents">
                <strong>@lang('common.table-of-contents')</strong>
                {!! $liveView->getTableOfContents() !!}
            </div>
        </aside>
        <main class="column is-two-thirds">
            {!! $liveView->getBody() !!}
        </main>
    </div>
@stop

@section ('scripts')
    <script type="text/javascript" src="{{ asset('/js/spec.js') }}"></script>
@stop
