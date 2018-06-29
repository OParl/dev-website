@extends ('base')

@section ('subheader')
    <div class="level-item">
        <a href="{{ route('downloads.index') }}">@lang('app.downloads.title')</a>
    </div>
@stop

@section ('subheader_actions')
    <div class="level-item">
        <b-dropdown>
            <button class="button" slot="trigger">{{ $liveView->getVersionInformation()['official'] }}</button>
            @foreach (config('oparl.versions.specification') as $version => $constraint)
                <b-dropdown-item @click="changeLiveView('{{ $version }}')">{{ $version }}</b-dropdown-item>
            @endforeach
        </b-dropdown>
    </div>
@stop

@section ('content')
    <div class="columns">
        <aside class="column is-one-third">
            <affix relative-element-selector="#spec-content">
                <b-collapse class="card" :open="false">
                    <div class="card-header" slot="trigger" slot-scope="props">
                        <strong class="card-header-title">@lang('common.table-of-contents')</strong>
                        <div class="card-header-icon">
                            <b-icon :icon="props.open ? 'expand' : 'compress'"></b-icon>
                        </div>
                    </div>
                    <div class="card-content table-of-contents">
                        {!! $liveView->getTableOfContents() !!}
                    </div>
                </b-collapse>
            </affix>
        </aside>
        <div class="column is-two-thirds" id="spec-content">
            {!! $liveView->getBody() !!}
        </div>
    </div>
@stop
