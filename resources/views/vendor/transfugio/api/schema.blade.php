<h4>Kurzbeschreibung</h4>

<p>
    @press($documentation->getModelDescription())
</p>

<f-accordion>
    <span slot="title">Mehr&hellip;</span>
    <div slot="body" c>
        <h4 >Eigenschaften</h4>
        <table style="min-width: 500px; overflow: scroll">
            <thead>
                <tr>
                    <th>Feldname</th>
                    <th>JSON Datentyp</th>
                    <th>Beschreibung</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($documentation->getFields() as $field)
                <tr>
                    <td>
                        @if ($field->isRequired())
                            <strong aria-label="Zwingende Eigenschaft">{{ $field->getName() }}</strong>
                        @elseif(isset($schema['oparl:recommended']) && in_array($name, $schema['oparl:recommended']))
                            <em aria-label="Empfohlene Eigenschaft">{{ $name }}</em>
                        @else
                            {{ $field->getName() }}
                        @endif
                    </td>
                    <td>
                        @include ('transfugio::api.schema.type_or_type_list')
                    </td>
                    <td>
                        @if ($field->hasDescription())
                            <div class="text-muted small">
                                @press($field->getDescription())
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="small">
            <h4 class="text-muted">Legende</h4>
            <ul class="list-unstyled">
                <li><strong>Zwingende Eigenschaft</strong></li>
                <li><em>Empfohlene Eigenschaft</em></li>
            </ul>
        </div>
    </div>
</f-accordion>
