@extends('base')

@section ('subheader')
    @include ('developers.partials.subheader')
@stop

@section('content')
    <section class="section">
        @foreach ($endpoints as $endpoint)
            <div class="card">
                <div class="panel panel--default">
                    <div class="row">
                        <h2 class="col-xs-11">{{ $endpoint['title'] }}</h2>
                        <div class="col-xs-1">
                            <button
                                    type="button"
                                    v-clipboard:copy="'{{ $endpoint['url'] }}'"
                            >
                                <i class="fa fa-copy" title="@lang('app.endpoints.copy')"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        @if (isset($endpoint['description']))
                            @press($endpoint['description'])
                        @endif

                        <pre class="small"><code>{{ $endpoint['url'] }}</code></pre>
                    </div>
                </div>
            </div>
        @endforeach
    </section>
@endsection

@section ('scripts')
    <script type="text/javascript" src="{{ asset('js/developers.js') }}"></script>
@stop