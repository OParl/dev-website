<b-collapse class="card" :open="false">
    <div slot="trigger" slot-scope="props" class="card-header">
        <p class="card-header-title">
            {{ $exampleTitle }}
        </p>
        <a class="card-header-icon">
            <b-icon :icon="props.open ? 'expand' : 'compress'"></b-icon>
        </a>
    </div>
    <div class="card-content">
        {!! $exampleCode !!}
    </div>
</b-collapse>
<br>
