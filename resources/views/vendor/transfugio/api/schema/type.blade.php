@if ($type !== "array")
    <samp>{{ $type }}</samp>
@else
    <samp>array of {{ $property['items']['type'] }}</samp>
@endif
