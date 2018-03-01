@extends ('base')

@section ('subheader')
    <div class="level-item">
        <a href="{{ route('downloads.index') }}">@lang('app.downloads.title')</a>
    </div>
@stop

@section ('subheader_actions')
    <div class="level-item">
        <affix relative-element-selector=".specification" class="is-fluid">

        <b-dropdown position="is-bottom-left">
            <a class="navbar-item" slot="trigger">
                <span>@lang('common.table-of-contents')</span>
                <b-icon icon="menu-down"></b-icon>
            </a>
            <b-dropdown-item custom>
                {!! $liveView->getTableOfContents() !!}
            </b-dropdown-item>
        </b-dropdown>
        </affix>
    </div>
@stop

@section ('content')
    <div class="section specification">
        {!! $liveView->getBody() !!}
    </div>
@stop

@section ('scripts')
    <script type="text/javascript" src="{{ asset('js/spec.js') }}"></script>
@stop
