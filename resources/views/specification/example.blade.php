<b-collapse class="card">
    <div slot="trigger" slot-scope="props" class="card-header">
        <p class="card-header-title">
            {{ $exampleTitle }}
        </p>
        <a class="card-header-icon">
            <b-icon
                    :icon="props.open ? 'menu-down' : 'menu-up'">
            </b-icon>
        </a>
    </div>
    <div class="card-content">
        <div class="content">
            {!! $exampleCode !!}
        </div>
    </div>
</b-collapse>
