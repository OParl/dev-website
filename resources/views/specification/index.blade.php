@extends ('base')

@section ('subheader')
    <li class="col-xs-10">
        {{--<span title="Git: {{ substr($liveversion->getHash(), 0, 6) }}">OParl {{ $liveversion->getVersion() }}</span>--}}
        &nbsp;
    </li>
    <li class="col-xs-2">
        <button @click="toggleTableOfContents" title="@lang('common.table-of-contents')" aria-label="@lang('common.table-of-contents')">
        <i class="fa fa-2x fa-book" aria-hidden="true"></i>
        </button>
        <button @click="showDownloadsModal = true" title="@lang('app.specification.download.title')"
            aria-label="@lang('app.specification.download.title')">
            <i class="fa fa-2x fa-download" aria-hidden="true"></i>
        </button>
    </li>
@stop

@section ('content')
    <f-accordion :has-trigger="true" ref="tableOfContents">
        <div slot="body">{!! $liveView->getTableOfContents() !!}</div>
    </f-accordion>

    {!! $liveView->getBody() !!}

    <f-modal v-if="showDownloadsModal" @close="showDownloadsModal = false">
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
    @if ($app->environment('production'))
        <script type="text/javascript" src="{{ asset('js/spec.min.js') }}"></script>
    @else
        <script type="text/javascript" src="{{ asset('js/spec.js') }}"></script>
    @endif
@stop
