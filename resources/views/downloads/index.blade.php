@extends ('base')

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
                            <div class="field is-grouped is-grouped-multiline">
                                @foreach ($specificationVersion->getFiles() as $file)
                                    <div class="control">
                                        <a
                                                href="{{ route('downloads.specification', ['version' => $specificationVersion->getVersion(), 'format' => $file->getExtension()]) }}"
                                                title="@lang('app.specification.download.select.title', ['version' => $specificationVersion->getVersion(), 'format' => $file->getExtension()])"
                                        >

                                            <div class="tags has-addons">
                                            <span class="tag">

                                                    {{ $file->getExtension() }}

                                            </span>

                                                <span class="tag is-primary">{{ $specificationVersion->getVersion() }}</span>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>

                @endforeach
            </form>
            </tbody>
        </table>
    </section>
@stop
