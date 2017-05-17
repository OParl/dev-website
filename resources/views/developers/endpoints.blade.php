@extends('base')

@section ('subheader')
    @include ('developers.partials.subheader')
@stop

@section('content')
    {{--
    <section class="row">
        <h2 class="col-xs-12 col-md-3">@lang('app.endpoints.title')</h2>
        <div class="col-xs-12 col-md-9">
            @press(trans('app.endpoints.text'))
        </div>
    </section>
    --}}

    <section class="row">
    @foreach ($endpoints as $endpoint)
        <div class="col-xs-12 col-sm-6">
            <div class="panel panel--default">
                <h2>{{ $endpoint['title'] }}</h2>

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