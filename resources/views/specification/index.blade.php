@extends ('base')

@section ('subheader')
    <li class="row-item">
        <button class='zondicon' title="@lang('common.table-of-contents')">
            @svg('img/icons/book-reference.svg')
        </button>
    </li>
    <li class="row-item">
        <span title="Git: {{ substr($liveversion->getHash(), 0, 6) }}">{{ $liveversion->getVersion() }}</span>
    </li>
    <li class="row-item">
        <button class='zondicon' @click="showDownloadsModal = true" title="@lang('app.specification.download.title')">
            @svg('img/icons/download.svg')
        </button>
    </li>

    {{-- TODO: implement navigation as sticky submenu --}}
@stop

@section ('content')
    {!! $liveversion->getContent() !!}

    <f-modal :show.sync="showDownloadsModal">
        <h3 slot="header">@lang('app.specification.download.select.title')</h3>
        <div slot="body">
            <p class="left">@lang('app.specification.download.formatinfo')</p>
            <div class="row row--center">
                <div>
                    <h3>@lang('app.specification.download.singlefile')</h3>
                    <ul>
                        <li>
                            <a href="{{ route('downloads.provide', [$builds->first()->short_hash, 'pdf']) }}">@lang('app.specification.download.format.pdf')</a>
                        </li>
                        <li>
                            <a href="{{ route('downloads.provide', [$builds->first()->short_hash, 'epub']) }}">@lang('app.specification.download.format.epub')</a>
                        </li>
                        <li>
                            <a href="{{ route('downloads.provide', [$builds->first()->short_hash, 'html']) }}">@lang('app.specification.download.format.html')</a>
                        </li>
                        <li>
                            <a href="{{ route('downloads.provide', [$builds->first()->short_hash, 'docx']) }}">@lang('app.specification.download.format.docx')</a>
                        </li>
                        <li>
                            <a href="{{ route('downloads.provide', [$builds->first()->short_hash, 'odt']) }}">@lang('app.specification.download.format.odt')</a>
                        </li>
                        <li>
                            <a href="{{ route('downloads.provide', [$builds->first()->short_hash, 'txt']) }}">@lang('app.specification.download.format.txt')</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3>@lang('app.specification.download.archives')</h3>
                    <p class="left">@lang('app.specification.download.archives-info')</p>
                    <ul>
                        <li>
                            <a href="{{ route('downloads.provide', [$builds->first()->short_hash, 'zip']) }}">@lang('app.specification.download.format.zip')</a>
                        </li>
                        <li>
                            <a href="{{ route('downloads.provide', [$builds->first()->short_hash, 'tar.gz']) }}">@lang('app.specification.download.format.targz')</a>
                        </li>
                        <li>
                            <a href="{{ route('downloads.provide', [$builds->first()->short_hash, 'tar.bz2']) }}">@lang('app.specification.download.format.tarbz2')</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </f-modal>
@stop

@section ('scripts')
    <script type="text/javascript" src="{{ asset('js/spec.js') }}"></script>
@stop
