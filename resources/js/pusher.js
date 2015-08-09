(function () {
    var pusher = new Pusher(__pusher_config.key, {
        encrypted: true
    });

    var channel = pusher.subscribe(__pusher_config.channel);
    channel.bind('App\\Events\\RequestedBuildFinished', function(data) {
        $('loading').hide();
        $('done').show();
    });
})();
