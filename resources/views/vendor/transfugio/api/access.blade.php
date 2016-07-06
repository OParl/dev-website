<div class="row">
    <div class="col-xs-12">
        <p>
            Diese Implementierung der OParl API bietet verschiedene Ausgabeformate. Zudem respektiert sie den
            in vielen Systemen gebräuchlichen <code>accept: application/json</code>-Header. Wenn dieser Header
            nicht benutzt werden kann/soll, oder ein anderes Format gewünscht ist, kann jedem Request
            ein <code>?format=[formatName]</code> beigefügt werden.
        </p>

        <h3>Unterstützte Formate</h3>

        <ul>
            <li><a href="{{ $url }}?format=html">html</a></li>
            <li><a href="{{ $url }}?format=json" target="_blank">json</a></li>
            <li><a href="{{ $url }}?format=yaml" target="_blank">yaml</a></li>
            {{--<li><a href="{{ $url }}?format=xml" target="_blank">xml</a></li>--}}
        </ul>

        <p>
            Hier sind einige Zugriffsbeispiele für diesen Endpunkt:
        </p>

        <h3>Shell</h3>

        <pre><code class="language-bash">$ curl "{{ $url }}?format=json"</code></pre>
        <pre><code class="language-bash">$ http --json get "{{ $url }}"</code></pre>

        <div class="pull-right">
            <p class="small">(* <a href="//github.com/jakubroztocil/httpie">http</a>)</p>
        </div>

        <h3>Javascript (<a href="//jquery.com">jQuery</a>)</h3>

        <pre><code class="language-javascript">@include('transfugio::api.examples.js')</code></pre>

        <h3>Python (<a href="//python-requests.org">Requests</a>)</h3>

        <pre><code class="language-python">@include('transfugio::api.examples.python')</code></pre>

        <h3>PHP (<a href="//guzzle.readthedocs.org/en/latest/">Guzzle</a>)</h3>

        <pre><code class="language-php">@include('transfugio::api.examples.php')</code></pre>
    </div>
</div>
