<template>
    <div class="fselect-selector-{{ label }}-container">
        <select class="fselect-selector-{{ label }}" style="width: 100%">
            <option
                    v-for="item in menuItems"
                    v-if="item.title.length > 0"
                    :value="item.link">
                {{ item.title }}
            </option>
        </select>
    </div>
</template>

<script>
    import Vue from 'vue'
    import VueResource from 'vue-resource'

    Vue.use(VueResource);

    export default {
        props: ['from', 'label'],

        data() {
            return {
                'menuItems': []
            }
        },

        activate(done) {
            let self = this;

            Vue.http.get(self.from).then(function (result) {
                self.menuItems = result.data;

                setTimeout(function () {
                    var selector = '.fselect-selector-' + self.label;
                    $(selector)
                            .select2()
                            .on('change', function (e) {
                                var anchor = e.target.selectedOptions[0].value;
                                document.location.hash = anchor;
                                $(anchor).scroll();
                            });
                }, 300);

                done();
            });
        }
    }
</script>
