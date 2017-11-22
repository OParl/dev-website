<footer class="row">
    <div class="c-footer col-xs-12 col-md-offset-2 col-md-8">
        <div class="row">
            <div class="col-xs-6 col-sm-4">
                <img
                        src="{{ asset('img/logos/okf.svg') }}"
                        alt="Logo der OpenKnowledge Foundation Deutschland" height="48"
                />
            </div>
            <div class="col-xs-6 col-sm-4 end-sm">
                <img src="{{ asset('img/logos/cfg.svg') }}" alt="Logo von Code for Germany" height="48" />
            </div>
            <div class="col-xs-12 col-sm-4 first-sm middle-sm">
                <a href="{{ route('locale.set', ['language' => 'de']) }}" title="Sprache auf Deutsch umstellen.">
                    <span class="flag-icon flag-icon-de flag-icon-squared u-round"></span>
                </a>
                <a href="{{ route('locale.set', ['language' => 'en']) }}" title="Switch language to English.">
                    <span class="flag-icon flag-icon-gb flag-icon-squared u-round"></span>
                </a>
            </div>
        </div>
    </div>
</footer>
