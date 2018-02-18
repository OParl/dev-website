@extends ('base')

@section('subheader')
    @include('developers.partials.subheader')
@stop

@section ('content')
    <section>
        <h2>@lang('app.downloads.title') - @lang('app.specification.title')</h2>

        <p>@lang('app.specification.download.formatinfo')</p>

        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Format</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <form action="{{ route('downloads.request') }}" class="pure-form" method="post">
                {{ csrf_field() }}

                @foreach ($specificationDownloads->all() as $specificationVersion)
                    <tr>
                        <td>
                            OParl Spezifikation {{ $specificationVersion->getVersion() }}
                        </td>
                        <td>
                            <select name="format[{{ $specificationVersion->getVersion() }}]" class="pure-input-1-2">
                                @foreach ($specificationVersion->getFiles() as $file)
                                    <option value="{{ $file->getExtension() }}">{{ $file->getExtension() }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td style="font-size: xx-small; text-align: right">
                            <button type="submit" class="pure-button" aria-label="Download" name="version" value="{{ $specificationVersion->getVersion() }}">
                                <img src="{{ asset('img/icons/download.svg') }}" aria-hidden="true">
                            </button>
                        </td>
                    </tr>

                @endforeach
            </form>
            </tbody>
        </table>
        {{--<div class="row">--}}
        {{--<div>--}}
        {{--<h3>@lang('app.specification.download.singlefile')</h3>--}}

        {{--@include('downloads.specification_singlefile_list')--}}
        {{--</div>--}}

        {{--<div>--}}
        {{--<h3>@lang('app.specification.download.archives')</h3>--}}
        {{--<p>@lang('app.specification.download.archives-info')</p>--}}

        {{--@include('downloads.specification_archives_list')--}}
        {{--</div>--}}
        {{--</div>--}}
    </section>

    {{--
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
    --}}
@stop