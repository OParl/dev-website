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
                                    @input="triggerDownload"
                            >
                                @foreach ($specificationVersion->getFiles() as $file)
                                    <option :value="{ version: '{{ $specificationVersion->getVersion() }}', format: '{{ $file->getExtension() }}'}">{{ $file->getExtension() }}</option>
                                @endforeach
                            </b-select>
                        </td>
                    </tr>

                @endforeach
            </form>
            </tbody>
        </table>
    </section>
@stop
