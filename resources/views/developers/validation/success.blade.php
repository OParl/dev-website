@extends ('base')

@section ('subheader')
    @include('developers.partials.subheader')
@stop

@section ('content')
    <p>
        {{ Session::get('message') }}
    </p>
    <p>
        {!! trans('app.validation.explanation.duration', ['contact_url' => route('contact.index')]) !!}
    </p>
@stop