@extends ('base')

@section ('content')
    @yield ('about-content')

    <footer>
        <div class="row">
            <strong class="text-tiny">OParl wird unterstützt von:</strong>

            <ul class="list-unstyled list-inline">
                <li>
                    <a href="http://www.vitako.de">
                        <img src="{{ asset('img/vitako-logo-xs.png') }}"
                             alt="VITAKO Bundesarbeitsgemeinschaft der kommunalen IT-Dienstleister"/>
                    </a>
                </li>
                <li>
                    <a href="http://okfn.de">
                        <img src="{{ asset('img/okf-de-logo-xs.png') }}"
                             alt="OpenKnowledge Foundation Deutschland e.V."/>
                    </a>
                </li>
            </ul>
        </div>
    </footer>
@stop