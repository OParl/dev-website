@extends ('base')

@section ('subheader')
    <li class="col-xs-12 col-sm-2">
        <a href="{{ route('downloads.index') }}">@lang('app.downloads.title')</a>
    </li>
@stop

@section ('content')
    {!! $liveView->getTableOfContents() !!}

    {!! $liveView->getBody() !!}
@stop

@section ('scripts')
    <script type="text/javascript" src="{{ asset('js/spec.js') }}"></script>
@stop
