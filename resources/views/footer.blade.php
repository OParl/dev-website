<footer class="footer level">
        <div class="level-left">
            <div class="level-item">
                <a href="{{ route('locale.set', ['language' => 'de']) }}" title="Sprache auf Deutsch umstellen.">
                    <span class="flag-icon flag-icon-de flag-icon-squared shape--circle"></span>
                </a>
            </div>
            <div class="level-item">
                <a href="{{ route('locale.set', ['language' => 'en']) }}" title="Switch language to English.">
                    <span class="flag-icon flag-icon-gb flag-icon-squared shape--circle"></span>
                </a>
            </div>
            <div class="level-item">
                <a href="https://oparl.org/impressum">@lang('app.imprint')</a>
            </div>
            <div class="level-item">
                <a href="https://oparl.org/datenschutz/">@lang('app.privacy')</a>
            </div>
        </div>

        <div class="level-right">
            <div class="level-item">
                <img
                        src="{{ asset('images/logos/okf.svg') }}"
                        alt="Logo der OpenKnowledge Foundation Deutschland"
                        class="height--48"
                />
            </div>
            <div class="level-item">
                <img src="{{ asset('images/logos/cfg.svg') }}" alt="Logo von Code for Germany" class="height--48" />
            </div>
        </div>
</footer>
