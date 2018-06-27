import Vue from './vue-base'
const vm = new Vue({
    el: '#app',
    methods: {
        triggerDownload(download) {
            document.location = '/downloads/spezifikation-' + download.version + '.' + download.format;
        }
    }
});

window.vm = vm;
