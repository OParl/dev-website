<button class="btn btn-default" data-target="#{{ $exampleIdentifier }}"
   aria-controls="{{ $exampleIdentifier }}" data-toggle="collapse"
   aria-expanded="false" role="button">
    {{ $exampleTitle }}
</button>
<div class="collapse" id="{{ $exampleIdentifier }}">
    {!! $exampleCode !!}
</div>
