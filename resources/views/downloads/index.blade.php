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
                            <b-select
                                    name="format[{{ $specificationVersion->getVersion() }}]"
                                    placeholder="@lang('app.specification.download.select.title')"
                                    expanded
                            >
                                @foreach ($specificationVersion->getFiles() as $file)
                                    <option value="{{ $file->getExtension() }}">{{ $file->getExtension() }}</option>
                                @endforeach
                            </b-select>
                        </td>
                        <td style="font-size: xx-small; text-align: right">
                            <button class="button is-dark"
                                    type="submit"
                                    aria-label="Download"
                                    name="version"
                                    value="{{ $specificationVersion->getVersion() }}"
                            >
                                <b-icon icon="download"></b-icon>
                            </button>
                        </td>
                    </tr>

                @endforeach
            </form>
            </tbody>
        </table>
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

@section ('scripts')
    <script type="text/javascript" src="{{ asset('js/developers.js') }}"></script>
@stop
