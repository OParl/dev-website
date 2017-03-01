export default {
    prismURLHelper() {
        // make links in code blocks clickable
        Prism.hooks.add('wrap', function(env) {
            if (env.type == 'string' && env.content.match(/http/))
            {
                let url = env.content.replace('"', '');
                url = url.replace("'", '');
                let displayURL = env.content.replace(/\?format=html/, '');
                env.content = "<a href=\"" + url + "\">" + displayURL + "</a>";
            }
        });
    }
}

