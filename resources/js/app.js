$(document).ready(function () {
    // hide email field on downloads page
    $('#download-selector .form-group:nth-of-type(2)').hide();

    // convert chapter remarks to end-of-page remarks
    $('.chapter').each(function (i, chapter) {
        /*
          - get footnotes
          - append chapter number (i)
          - find corresponding a[name]
          - change title to consecutive numbering and name to include chapter number
          - append footnotes to #endnotes
        */

        var remarks = $('.footnotes ol', chapter);

        $('#endnotes').append(remarks);
        $('.footnotes').remove();
    });

    // conditionally show email field on downloads page
    $('#download-selector select').on('change', function(event) {
        if (event.target.selectedOptions[0].attributes[0].value == "0")
        {
            $('#download-selector .form-group:nth-of-type(2)').slideDown(1200);
        }
    });
});
