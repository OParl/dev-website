export default {
    prismURLHelper() {
        // make links in code blocks clickable
        Prism.hooks.add('wrap', function(env) {
            if (env.type == 'string' && env.content.match(/http/))
            {
                var url = env.content.replace('"', '');
                url = url.replace("'", '');
                var displayURL = env.content.replace(/\?format=html/, '');
                env.content = "<a href=\"" + url + "\">" + displayURL + "</a>";
            }
        });
    }
}

