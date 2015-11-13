<section aria-label="Nachrichtenarchiv">
    <h3>Archiv</h3>
    <ul class="list-unstyled">
        @forelse ($archiveList as $year => $months)
            <li>
                <h4><a class="link-modest" href="{{ route('news.yearly', $year) }}">{{ $year }}</a></h4>
                <ul class="list-unstyled">
                    @foreach ($months as $month)
                        <li>
                            <a class="link-modest" href="{{ route('news.monthly', [$year, $month['numeric']]) }}">{{ $month['name'] }}</a>
                            ({{ $month['count'] }})
                        </li>
                    @endforeach
                </ul>
            </li>
        @empty
            {{-- NOTE: The case that no archive is available only exists on an unseeded system. --}}
        @endforelse
    </ul>
</section>