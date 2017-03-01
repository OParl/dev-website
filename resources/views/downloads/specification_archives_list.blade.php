<ul>
    <li>
        <a href="{{ route('downloads.specification', ['1.0', 'zip']) }}">
            @lang('app.specification.download.format.zip')
        </a>
    </li>
    <li>
        <a href="{{ route('downloads.specification', ['1.0', 'tar.gz']) }}">
            @lang('app.specification.download.format.targz')
        </a>
    </li>
    <li>
        <a href="{{ route('downloads.specification', ['1.0', 'tar.bz2']) }}">
            @lang('app.specification.download.format.tarbz2')
        </a>
    </li>
</ul>