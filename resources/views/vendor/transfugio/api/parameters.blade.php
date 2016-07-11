<div class="row">
    <div class="col-xs-12">
        <p>
            Zur Einschränkung der ausgegebenen Daten bietet die OParl API die
            folgende Parametersyntax an:
        </p>
        <pre><code class="language-bash">@include ('transfugio::api.examples.http_parameters')</code></pre>
        <dl class="dl-horizontal">
            <dt>format</dt>
            <dd>
                <div><strong>Datentyp:&nbsp;</strong><samp>string</samp></div>

                <p>
                    Der <code>format</code>-Parameter gibt das Ausgabeformat an.
                    Weitere Informationen dazu finden sich im Tab
                    <a href="#access" data-toggle="tab" aria-controls="access">Zugriff</a>.
                </p>
            </dd>
            <dt>where</dt>
            <dd>
                <div><strong>Datentyp:&nbsp;</strong><samp>string</samp></div>
                <div>
                    <strong>Format:&nbsp;</strong><samp>(feld:wert ?)+</samp>
                </div>

                Der <code>where</code>-Parameter ermöglicht das Filtern der Ausgabe von Listen.
                Er hat keine Wirkung auf die Ausgabe von einzelnen Entities. Das Format der
                Suchanfragen orientiert sich an <a href="//www.elastic.co/guide/en/elasticsearch/reference/1.x/query-dsl-query-string-query.html#query-string-syntax">Elastic Search</a>.
            </dd>
            <dt>page</dt>
            <dd>
                <div><strong>Datentyp:&nbsp;</strong><samp>integer</samp></div>
                <div><strong>Wertebereich:&nbsp;</strong><samp>[1,&infin;)</samp></div>

                Der <code>page</code>-Parameter ermöglicht das Wechseln zwischen den
                verschiedenen Seiten einer Listenausgabe. Er wird ausgewertet, sobald
                eine Abfrage {{ config('oparl.itemsPerPage') }} oder den in <code>limit</code>
                angegebenen Wert an Elementen übersteigt.
            </dd>
            <dt>limit</dt>
            <dd>
                <div><strong>Datentyp:&nbsp;</strong><samp>integer</samp></div>
                <div><strong>Wertebereich:&nbsp;</strong><samp>[0,&infin;)</samp></div>

                <p>
                    Mit dem <code>limit</code>-Parameter lässt sich die Anzahl von ausgegebenen
                    Listenelementen beschränken. Der Parameter funktioniert – ebenso wie
                    <code>where</code> – nur auf Listenanfragen und hat keine Wirkung,
                    wenn einzelne Entities abgefragt werden.
                </p>

                <p>
                    Die Angabe von <code>limit=0</code> führt zur selben Ausgabe wie das Weglassen
                    des Parameters. Standardmäßig werden dann maximal {{ config('oparl.itemsPerPage') }}
                    Elemente pro Seite ausgegeben.
                </p>
            </dd>

            <dt>only</dt>
            <dd>
                <p>
                    Der <code>only</code>-Parameter ermöglicht es, die Ausgabe auf den Daten- oder
                    den Metaabschnitt zu reduzieren. Die Angabe des Parameters ist <em>optional</em>
                    und wird sowohl bei Listen, als auch bei Einzelelementen ausgewertet.
                </p>

                <p>
                    Der Parameter kann folgende Werte annehmen:
                </p>

                <ul>
                    <li><samp>data</samp>: Zeige nur Datenabschnitt</li>
                    <li><samp>meta</samp>: Zeige nur Metaabschnitt</li>
                    <li>
                        <samp>data,meta</samp>: Zeige Daten- und Metaabschnitt
                        (Äquivalent zum Weglassen des Parameters.)
                    </li>
                </ul>
            </dd>
        </dl>

    </div>
</div>