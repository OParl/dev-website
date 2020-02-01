@extends ('base')

@section ('content')
    <div class="columns">
        <aside class="column is-one-third" id="toc-container">
            <affix relative-element-selector="#spec-content" :style="formattedAffixWidth">
                <nav class="panel table-of-contents" aria-labelledby="toc-header">
                    <div class="panel-heading">
                        <div class="columns">
                            <div class="column is-vcentered">
                                <strong id="toc-header">Inhaltsverzeichnis</strong>
                            </div>
                            <div class="column is-narrow">
                                <version-selector
                                        :versions="{{ json_encode(array_keys(config('oparl.versions.specification'))) }}"
                                        current-official="{{$liveView->getOfficialVersion() }}"
                                        current-hash="{{ $liveView->getGitHash() }}"
                                        @version-change="changeLiveView"
                                ></version-selector>
                            </div>
                        </div>
                    </div>
                    {!! $liveView->getTableOfContents() !!}
                    <div style="height: 1vh">&nbsp;</div>
                </nav>
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
