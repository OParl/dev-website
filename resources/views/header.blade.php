<header role="navigation">
    <nav class="row">
        <span class="col-xs-12 col-sm-4">
            <img src="{{ asset('img/logos/oparl.svg') }}" alt="OParl Logo" height="48"
                 longdesc="'OParl.' in hellblauer Schrift auf weißem Grund.">
        </span>
        <div class="col-xs-11 col-sm-6">
            <ul class="row">
                @foreach ($sections as $section)
                    <li class="col-sm-6 col-md-3 @if (isset($section['current']) && $section['current']) active @endif">
                        <a href="{{ $section['href'] }}" @if (isset($section['url'])) target="_blank" @endif>
                            {{--<i class="fa {{ $section['icon'] }}" aria-hidden="true"></i>--}}
                            <span>@lang($section['title'])</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-xs-1 col-sm-2 pull-right">
            <ul>
                <li><a href="{{ route('locale.set', ['language' => 'de']) }}"><abbr
                                title="Sprache auf Deutsch umstellen.">DE</abbr></a></li>
                <li><a href="{{ route('locale.set', ['language' => 'en']) }}"><abbr title="Switch language to English.">EN</abbr></a>
                </li>
            </ul>
        </div>
    </nav>
    <nav>
        <ul class="row center-xs">
            @yield('subheader')
        </ul>
    </nav>
</header>
