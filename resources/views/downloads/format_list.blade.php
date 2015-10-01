<div class="col-xs-12 col-md-6">
    <h3>Ausgabeformate</h3>
    <ul>
        <li><a href="{{ route('downloads.provide', [$version->short_hash, 'docx']) }}">Microsoft Word</a></li>
        <li><a href="{{ route('downloads.provide', [$version->short_hash, 'epub']) }}">ePub</a></li>
        <li><a href="{{ route('downloads.provide', [$version->short_hash, 'odt']) }}">OpenOffice.org Text</a></li>
        <li><a href="{{ route('downloads.provide', [$version->short_hash, 'pdf']) }}">PDF</a></li>
        <li><a href="{{ route('downloads.provide', [$version->short_hash, 'html']) }}">HTML (Standalone)</a></li>
        <li><a href="{{ route('downloads.provide', [$version->short_hash, 'txt']) }}">Plain Text</a></li>
    </ul>
</div>
<div class="col-xs-12 col-md-6">
    <h3>Archive</h3>
    <p class="text-muted">Die Archive enthalten alle Ausgabeformate.</p>
    <ul>
        <li><a href="{{ route('downloads.provide', [$version->short_hash, 'zip']) }}">Zip</a></li>
        <li><a href="{{ route('downloads.provide', [$version->short_hash, 'tar.gz']) }}">Gzip</a></li>
        <li><a href="{{ route('downloads.provide', [$version->short_hash, 'tar.bz2']) }}">Bzip2</a></li>
    </ul>
</div>
