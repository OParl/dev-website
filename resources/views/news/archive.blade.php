<section aria-label="Nachrichtenarchiv">
    <h3>Archiv</h3>
    <ul class="list-unstyled">
        @foreach ($archiveList as $year => $months)
            <li>
                <h4><a class="link-modest" href="{{ route('news.yearly', $year) }}">{{ $year }}</a></h4>
                <ul class="list-unstyled">
                    @foreach ($months as $month)
                        <li>
                            <a class="link-modest" href="{{ route('news.monthly', $month['numeric']) }}">{{ $month['name'] }}</a>
                            ({{ $month['count'] }})
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</section>