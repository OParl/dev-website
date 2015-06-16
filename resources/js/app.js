$(document).ready(function () {
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
});
