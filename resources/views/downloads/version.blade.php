<option value="{{ $version->hash }}" {{ (isset($selected) && $selected) ? 'selected' : '' }}>

    {{ $version->human_version }} ({{ $version->short_hash }}
    vom {{ $version->created_at->formatLocalized('%d.%m.%Y') }})

</option>
