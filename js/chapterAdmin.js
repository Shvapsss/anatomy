$(function () {
    var el = $('#chapter-grid tbody')[0];
    Sortable.create(el,{
        handle: ".handle",
        animation: '150',
        onEnd: function (evt) {
            var ar = [];
            $('#chapter-grid tbody tr').each(function( index ) {
                ar.push($(this).data('id'));
            });
            $.post('/site/sort',{sorted:ar});
        },
    });
});