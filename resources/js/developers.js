import Vue from 'vue'

Vue.directive('fixed', {
    bind: function () {
        this.el.className += " fixedsticky";
        this.el.css()
    }
});

const vm = new Vue({
    el: 'body'
});

window.vm = vm;
