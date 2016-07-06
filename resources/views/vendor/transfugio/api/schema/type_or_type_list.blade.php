@if (is_array($field->getType()))
    @foreach ($field->getType() as $type)
        @include ('transfugio::api.schema.type', compact('type'))
    @endforeach
@else
    @include ('transfugio::api.schema.type', ['type' => $field->getType()])
@endif
