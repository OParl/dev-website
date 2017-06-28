<h4>Kurzbeschreibung</h4>

<p>
    @press($documentation->getModelDescription())
</p>

<f-accordion>
    <span slot="title">Mehr&hellip;</span>
    <div slot="body" c>
        <h4 >Eigenschaften</h4>
        <table >
            <thead>
                <tr>
                    <th>Feldname</th>
                    <th>JSON Datentyp</th>
                    <th>Beschreibung</th>
                </tr>
            </thead>
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

                        </td>
                        <td>

                        </td>
                    </tr>
                @endforeach
            <tbody>

            </tbody>
        </table>

        {{--
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
        --}}

        <div class="small">
            <h4 class="text-muted">Legende</h4>
            <ul class="list-unstyled">
                <li><strong>Zwingende Eigenschaft</strong></li>
                <li><em>Empfohlene Eigenschaft</em></li>
            </ul>
        </div>
    </div>
</f-accordion>
