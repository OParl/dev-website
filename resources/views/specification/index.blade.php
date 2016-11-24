@extends ('base')

@section ('subheader')
    <li class="row-item">
        <button @click="showTableOfContents = !showTableOfContents" title="@lang('common.table-of-contents')" aria-label="@lang('common.table-of-contents')">
            <i class="fa fa-2x fa-book" aria-hidden="true"></i>
        </button>
    </li>
    <li class="row-item">
        <span title="Git: {{ substr($liveversion->getHash(), 0, 6) }}">OParl {{ $liveversion->getVersion() }}</span>
    </li>
    <li class="row-item">
        <button @click="showDownloadsModal = true" title="@lang('app.specification.download.title')"
            aria-label="@lang('app.specification.download.title')">
            <i class="fa fa-2x fa-download" aria-hidden="true"></i>
        </button>
    </li>
@stop

@section ('content')
    <f-accordion :show.sync="showTableOfContents" :has-trigger="true">
        <div slot="body">{!! $liveversion->getNav() !!}</div>
    </f-accordion>

    {!! $liveversion->getContent() !!}

    <f-modal :show.sync="showDownloadsModal">
        <h3 slot="header">@lang('app.specification.download.select.title')</h3>
        <div slot="body">
            <p class="left">@lang('app.specification.download.formatinfo')</p>
            <div class="row row--center">
                <div>
                    <h3>@lang('app.specification.download.singlefile')</h3>

                    @include('downloads.specification_singlefile_list')
                </div>
                <div>
                    <h3>@lang('app.specification.download.archives')</h3>
                    <p class="left">@lang('app.specification.download.archives-info')</p>

                    @include('downloads.specification_archives_list')
                </div>
            </div>
        </div>
    </f-modal>
@stop

@section ('scripts')
    <script type="text/javascript" src="{{ asset('js/spec.js') }}"></script>
@stop
