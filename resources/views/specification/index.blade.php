@extends ('base')

@section('header_extra')
    <nav class="level u-pt-0_5">
        <div class="level-left">&nbsp;</div>
        <div class="level-right">
            <div class="level-item">
                <version-selector
                        :versions="{{ json_encode(array_keys(config('oparl.versions.specification'))) }}"
                        :current="liveView.currentVersion.human"
                        @version-change="changeLiveView"
                ></version-selector>
            </div>
        </div>
    </nav>
@stop

@section ('content')
    <div class="columns">
        <aside class="column is-one-third" id="toc-container">
            <affix relative-element-selector="#spec-content" :style="formattedAffixWidth">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-header-title">Inhaltsverzeichnis</strong>
                    </div>
                    <div class="card-content table-of-contents" v-html="liveView.toc" v-if="!liveView.isLoading"></div>
                </div>
            </affix>
        </aside>
        <div class="column is-two-thirds" id="spec-content">
            <live-view :html="liveView.body" v-if="!liveView.isLoading"></live-view>
            <div style="height: 3em;" v-else>
                <b-loading :is-full-page="false" :active="liveView.isLoading"></b-loading>
            </div>
        </div>
    </div>
@stop

@section ('bundle')
    <script src="{{ asset('js/spec.js') }}"></script>
@overwrite
