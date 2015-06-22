jQuery.expr[':'].like = function(a,i,m){
    return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
};

$(document).ready(function () {
    // hide email field on downloads page
    $('#download-selector .form-group:nth-of-type(2)').hide();

    // conditionally show email field on downloads page
    $('#download-selector select').on('change', function(event) {
        if (event.target.selectedOptions[0].attributes[0].value == "0")
        {
            $('#download-selector .form-group:nth-of-type(2)').slideDown(1200);
        }
    });

    // enable scroll spy
    if ($('#toc'))
    {
        $('#toc').affix({
            offset: {
                top: $('#toc').offset().top - 16,
                bottom: $('#toc').offset().bottom
            }
        }).width($('#toc').parent().width());

        $('#toc ul').addClass('nav');

        $('#toc input').change(function (event) {
            // kudos: http://kilianvalkhof.com/2010/javascript/how-to-build-a-fast-simple-list-filter-with-jquery/

            var filter = $(this).val();

            if (!filter)
            {
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

        }).keyup(function () { $(this).change(); });

        //$('body').scrollspy({ target: '#toc > div' });

        $('#toc').parent().fadeIn();
    }
});
