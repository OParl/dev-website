@extends('base')

@section ('subheader')
    @include ('developers.partials.subheader')
@stop

@section('content')
    <section class="section">
        <table class="table" v-if="endpoints.length > 0">
            <tbody>
                <endpoint-info
                        v-for="endpoint in endpoints"

                        :key="endpoint.id"
                        :endpoint="endpoint"
                ></endpoint-info>
            </tbody>
        </table>
        <div v-else style="height: 3em">
            <b-loading :is-full-page="false" :active="endpoints.length == 0"></b-loading>
        </div>
    </section>

    <section class="section">
        <endpoint-statistics :endpoints="endpoints"></endpoint-statistics>
    </section>
@endsection
