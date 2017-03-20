<template>
    <div class="c-oparl-client">

    </div>
</template>

<script>
    import OParl from 'oparl';

    /*
    let client = OParl.open('', function (err, serverObj) {
        if (err !== null) {
            console.error('Something went wrong: ' + err);
        } else {
            console.log(serverObj);
        }
    });


    window.OParl = client;
     */

    export default {
        data() {
            return {
                url: document.location.href
            }
        }
    };
</script>