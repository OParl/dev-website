@extends ('base')

@section ('subheader')
    <div class="level-item">
        <a href="{{ route('downloads.index') }}">@lang('app.downloads.title')</a>
    </div>
@stop

@section ('content')
    <div class="columns">
        <aside class="column is-one-third">
            <b-collapse class="card">
                <div class="card-header" slot="trigger" slot-scope="props">
                    <strong class="card-header-title">@lang('common.table-of-contents')</strong>
                    <div class="card-header-icon">
                        <b-icon :icon="props.open ? 'menu-down' : 'menu-up'"></b-icon>
                    </div>
                </div>
                <div class="card-content table-of-contents">
                    {!! $liveView->getTableOfContents() !!}
                </div>
            </b-collapse>
        </aside>
        <main class="column is-two-thirds">
            {!! $liveView->getBody() !!}
        </main>
    </div>
@stop
