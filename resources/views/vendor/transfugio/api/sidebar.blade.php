<div class="panel panel--default">
    <div class="panel--heading">
        <h3>{{ $module }}</h3>
    </div>

    <div class="panel--body {{ strtolower($module) }} {{ $collectionClass }}" id="entity-documentation">
        @include ('transfugio::api.schema')
    </div>
    
    <div class="panel--footer">
        Mehr Informationen gibt es unter <a href="//oparl.org/spezifikation/">http://oparl.org/spezifikation/</a>.
    </div>
</div>
