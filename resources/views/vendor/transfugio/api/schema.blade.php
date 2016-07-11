<h4>Kurzbeschreibung</h4>

<p>
    @press($documentation->getModelDescription())
</p>

<h4>Eigenschaften</h4>
<dl class="properties">
@foreach ($documentation->getFields() as $field)
    <dt>
        @if ($field->isRequired())
            <span class="text-danger" aria-label="Zwingende Eigenschaft">{{ $field->getName() }}</span>

        @elseif(isset($schema['oparl:recommended']) && in_array($name, $schema['oparl:recommended']))
            <span class="text-success" aria-label="Empfohlene Eigenschaft">{{ $name }}</span>

        @else
            {{ $field->getName() }}
        @endif
    </dt>
    <dd>
        <div class="row">
            <div class="col-md-offset-1 col-md-11">
                <div class="type">
                    JSON-Datentyp: @include ('transfugio::api.schema.type_or_type_list')
                </div>
                @include('transfugio::api.schema.format')

                @if ($field->hasDescription())
                    <div class="text-muted small">
                        @press($field->getDescription())
                    </div>
                @endif
            </div>
        </div>
    </dd>
@endforeach
</dl>

<div class="well well-sm small hidden-xs">
    <h4 class="text-muted">Legende</h4>
    <ul class="list-unstyled">
        <li class="text-danger">Zwingende Eigenschaft</li>
        <li class="text-success">Empfohlene Eigenschaft</li>
    </ul>
</div>
