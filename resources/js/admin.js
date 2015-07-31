$('#textDeleteConfirmModal').on('show.bs.modal', function (event) {
    var provider = $(event.relatedTarget);

    var href = provider.data('href');
    var title = provider.data('title');

    $(this).find('.text-title').text(title);
    $(this).find('.btn-danger').attr('href', href);
});

$('#published_at_user_input').datetimepicker({
    inline: true,
    sideBySide: true,
    calendarWeeks: true,
    showClear: true,
    showTodayButton: true
});
