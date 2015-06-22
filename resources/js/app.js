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

        //$('body').scrollspy({ target: '#toc > div' });
    }
});
