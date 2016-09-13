<f-tab-list>
    <f-tab title="JSON">
        <pre><code class="language-javascript">{{ $json }}</code></pre>
    </f-tab>
    <f-tab title="Zugriff">
        @include ('transfugio::api.access')
    </f-tab>
    <f-tab title="Parameter">
        @include ('transfugio::api.parameters')
    </f-tab>
</f-tab-list>

{{--

<div class="main">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#json" aria-controls="json" data-toggle="tab">JSON</a></li>
        <li><a href="#access" aria-controls="access" data-toggle="tab">Zugriff</a></li>
        <li><a href="#parameters" aria-controls="parameters" data-toggle="tab">Parameter</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="json">
            <div class="row">
                <div class="col-xs-12">
                    <pre><code class="language-javascript">{{ $json }}</code></pre>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="access">
            @include ('transfugio::api.access')
        </div>
        <div class="tab-pane" id="parameters">
            @include ('transfugio::api.parameters')
        </div>
    </div>
</div>

--}}
