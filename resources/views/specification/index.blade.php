@extends ('base')

@section ('subheader')
    <div class="level-item">
        <a href="{{ route('downloads.index') }}">@lang('app.downloads.title')</a>
    </div>
@stop

@section ('subheader_actions')
    <div class="level-item">
        <b-dropdown v-model="navigation" position="is-bottom-left">
            <a class="navbar-item" slot="trigger">
                <span>@lang('common.table-of-contents')</span>
                <b-icon icon="menu-down"></b-icon>
            </a>
            <b-dropdown-item custom paddingless>
                {!! $liveView->getTableOfContents() !!}
            </b-dropdown-item>
        </b-dropdown>
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
