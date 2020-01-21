@extends ('base')

@section ('content')
    <div class="columns">
        <aside class="column is-one-third" id="toc-container">
            <affix relative-element-selector="#spec-content" :style="formattedAffixWidth">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-header-title">Inhaltsverzeichnis</strong>
                        <div class="card-header-icon">
                            <version-selector
                                    :versions="{{ json_encode(array_keys(config('oparl.versions.specification'))) }}"
                                    current="{{ $liveViewVersion }}"
                                    @version-change="changeLiveView"
                            ></version-selector>
                        </div>
                    </div>
                    <div class="card-content table-of-contents">
                        {!! $liveView->getTableOfContents() !!}
                    </div>
                </div>
            </affix>
        </aside>
        <div class="column is-two-thirds" id="spec-content">
            {!! $liveView->getBody() !!}
        </div>
    </div>
@stop

@section ('bundle')
    <script src="{{ asset('js/spec.js') }}"></script>
@overwrite
