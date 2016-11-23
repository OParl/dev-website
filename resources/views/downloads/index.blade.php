@extends ('base')

@section('subheader')
    <li><a href="{{ route('downloads.index') }}">@lang('app.downloads.title')</a></li>
@stop

@section ('content')
    <section>
        <h2>@lang('app.specification.title')</h2>

        <p>@lang('app.specification.download.formatinfo')</p>

        <div class="row">
            <div>
                <h3>@lang('app.specification.download.singlefile')</h3>

                <ul>
                    <li>
                        <a href="{{ route('downloads.latest', ['pdf']) }}">
                            @lang('app.specification.download.format.pdf')
                        </a>
                    </li>
                    <li>
                        <a href="">
                            @lang('app.specification.download.format.epub')
                        </a>
                    </li>
                    <li>
                        <a href="">
                            @lang('app.specification.download.format.html')
                        </a>
                    </li>
                    <li>
                        <a href="">
                            @lang('app.specification.download.format.docx')
                        </a>
                    </li>
                    <li>
                        <a href="">
                            @lang('app.specification.download.format.odt')
                        </a>
                    </li>
                    <li>
                        <a href="">
                            @lang('app.specification.download.format.txt')
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h3>@lang('app.specification.download.archives')</h3>
                <p>@lang('app.specification.download.archives-info')</p>
                <ul>
                    <li>
                        <a href="">
                            @lang('app.specification.download.format.zip')
                        </a>
                    </li>
                    <li>
                        <a href="">
                            @lang('app.specification.download.format.targz')
                        </a>
                    </li>
                    <li>
                        <a href="">
                            @lang('app.specification.download.format.tarbz2')
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section>
        <h2>@lang('app.downloads.liboparl.title')</h2>
        @press(trans('app.downloads.liboparl.description'))

        <div>
            <h3>@lang('app.downloads.liboparl.sourcecode')</h3>

            <ul>
                <li><code>git clone https://github.com/OParl/liboparl.git</code></li>
                <li>
                    <a href="https://github.com/OParl/liboparl/archive/master.zip">
                        @lang('app.downloads.liboparl.format.source_zip')
                    </a>
                </li>
            </ul>

            <h3>@lang('app.downloads.liboparl.packages')</h3>

            <ul>
                <li>
                    <a href="">
                        @lang('app.downloads.liboparl.format.debian_zip')
                    </a>
                </li>
                <li>
                    <a href="">
                        @lang('app.downloads.liboparl.format.macos_zip')
                    </a>
                </li>
                <li>@press(trans('app.downloads.liboparl.format.macos_brew'))</li>
            </ul>
        </div>
    </section>
@stop