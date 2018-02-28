@extends('base')

@section ('subheader')
    @include ('developers.partials.subheader')
@stop

@section('content')
    <section class="section">
        <table class="table">
            <tbody>
            @foreach ($endpoints as $endpoint)
                <tr>
                    <td>
                        {{ $endpoint['title'] }}
                    </td>
                    <td>
                        @if (isset($endpoint['description']))
                            @press($endpoint['description'])
                        @endif
                        <pre class="small"><code>{{ $endpoint['url'] }}</code></pre>
                    </td>
                    <td>
                        <button class="button">
                            <b-icon icon="copy"></b-icon>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>
@endsection

@section ('scripts')
    <script type="text/javascript" src="{{ asset('js/developers.js') }}"></script>
@stop