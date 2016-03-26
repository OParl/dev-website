jQuery.expr[':'].like = function(a,i,m){
    return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
};

$(document).ready(function () {
    if (document.location.pathname.match(/downloads/))     setupDownloads();
    if (document.location.pathname.match(/spezifikation/)) setupLiveCopy();
});

function setupDownloads()
{
    // enable select2
    $('#download-selector select').select2();
}

function setupLiveCopy() {
    $('#toc').affix({
        offset: {
            top: $('#toc').offset().top - 16,
            bottom: $('#toc').offset().bottom
         }
    });

    $('#toc input').change(function (event) {
        // kudos: http://kilianvalkhof.com/2010/javascript/how-to-build-a-fast-simple-list-filter-with-jquery/

        var filter = $(this).val();

        if (!filter) {
            $('#toc li').slideDown();
            return;
        }

        $('#toc div > ul').find("a:not(:like(" + filter + "))").parent().each(function () {
            if ($('ul', this).length == 0) {
                $(this).slideUp();
            } else if ($('ul', this).find('li:visible').length == 0) {
                $(this).slideUp();
            }
        });

        $('#toc div > ul').find("a:like(" + filter + ")").parent().slideDown();

    }).keyup(function () {
        $(this).change();
    });

    //$('body').scrollspy({ target: '#toc > div' });

    $('#toc').parent().fadeIn();
}
