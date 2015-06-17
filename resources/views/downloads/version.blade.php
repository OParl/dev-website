<option data-is-available="{{ $version->isAvailable() ? '1' : '0' }}"
        value="{{ $version->getHash() }}"
        {{ (isset($selected) && $selected) ? 'selected' : '' }}>
    {{ $version->getCommitMessage() }} ({{ $version->getHash(7) }}
    vom {{ $version->getDate()->formatLocalized('%d.%m.%Y') }})
</option>