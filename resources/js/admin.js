$('#textDeleteConfirmModal').on('show.bs.modal', function (event) {
    var provider = $(event.relatedTarget);

    var title = provider.data('title');
    var href = provider.data('href');

    $(this).find('.text-title').text(title);

    $(this).find('.btn-danger').attr('href', href);
});

$('#commentDeleteConfirmModal').on('show.bs.modal', function (event) {
    var provider = $(event.relatedTarget);

    var author = provider.data('author');
    var title = provider.data('title');
    var href = provider.data('href');

    $(this).find('.comment-author').text(author);
    $(this).find('.text-title').text(title);

    $(this).find('.btn-danger').attr('href', href);
});

$('#title').on('change', function (event) {
    var updateSlug = function () {
        $('#slug').attr('disabled', 'disabled');
        $('input[type=submit]').attr('disabled', 'disabled');
        $.post('/admin/posts/slug', {title: t.val()}, function (res) {
            $('#slug').val(res.slug);
            $('input[type=submit]').removeAttr('disabled');
            $('#slug').removeAttr('disabled').focus();
        });
    };

    var t = $(event.target);

    if ($('#post_title').data('is-published'))
    {
        $('#post_title').text('Eintrag “' + t.val() + '” bearbeiten');
    }

    // TODO: show info on outdated slug?

    updateSlug();
});

$('#tags').select2({
    tags: true
});

$('#published_at_user_input').datetimepicker({
    inline: true,
    sideBySide: true,
    calendarWeeks: true,
    showClear: true,
    showTodayButton: true,
    locale: 'de',
    toolbarPlacement: 'top',
    defaultDate: $('#published_at_input').val(),
    tooltips: {
        today: 'Heute',
        clear: 'Auswahl löschen (Nachricht wird damit wieder zu einem Entwurf.)',
        selectMonth: 'Monat auswählen',
        prevMonth: 'Vorheriger Monat',
        nextMonth: 'Nächster Monat',
        selectYear: 'Jahr auswählen',
        prevYear: 'Vorheriges Jahr',
        nextYear: 'Nächstes Jahr',
        selectDecade: 'Jahrzehnt auswählen',
        prevDecade: 'Vorheriges Jahrzehnt',
        nextDecade: 'Nächstes Jahrzehnt',
        selectCentury: 'Jahrhundert auswählen',
        prevCentury: 'Vorheriges Jahrhundert',
        nextCentury: 'Nächstes Jahrhundert'
    }
}).on('dp.change', function (event) {
    var text = 'Entwurf';

    var m = $(this).data('DateTimePicker').date();

    if (!m)
    {
        $('#published_at_input').val('');
    } else
    {
        $('#published_at_input').val(m.format());

        if (m.isBefore())
        {
            text = 'Veröffentlicht am ' + m.format('DD.MM.YYYY');
        } else
        {
            text = 'Zur Veröffentlichung geplant am ' + m.format('DD.MM.YYYY');
        }
    }

    $('#published_at_control span:first').text(text);
});

function updateCommentStatus(id, to)
{
    $.post('/admin/comments/status', {'status': to, 'id': id}, function (res) {
        $('.comment-'+res.id+'-status a').removeClass('active').removeAttr('aria-pressed');
        $('.comment-'+res.id+'-status a.'+res.status).addClass('active').attr('aria-pressed', 'true');
    });
}
