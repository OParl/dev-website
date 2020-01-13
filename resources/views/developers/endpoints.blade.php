@extends('base')

@section ('subheader')
    @include ('developers.partials.subheader')
@stop

@section('content')
    <h1>OParl Endpunkte</h1>

    <endpoint-list-filter
            :bodies="bodies"
            @endpoint-filter-list:filter="filterEndpointsByBody"
    ></endpoint-list-filter>

    <endpoint-info
            v-for="endpoint in filteredEndpoints"
            v-if="endpoints.length > 0"
            :key="endpoint.id"
            :endpoint="endpoint"
    ></endpoint-info>
    <div v-else style="height: 3em">
        <b-loading :is-full-page="false" :active="endpoints.length == 0"></b-loading>
    </div>

    <hr>
    <endpoint-statistics :endpoints="endpoints"></endpoint-statistics>
@stop

@section ('bundle')
    <script src="{{ mix('js/endpoints.js') }}"></script>
@overwrite
