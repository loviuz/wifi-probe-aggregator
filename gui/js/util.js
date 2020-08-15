// Modal
function openModal(title, href) {
    // Fix - Select2 does not function properly when I use it inside a Bootstrap modal.
    $.fn.modal.Constructor.prototype.enforceFocus = function () {
    };

    // Generazione dinamica modal
    do {
        id = '#bs-popup-' + Math.floor(Math.random() * 100);
    } while ($(id).length != 0);

    if ($(id).length == 0) {
        $('#modals').append('<div class="modal fade" id="' + id.replace("#", "") + '" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="true"></div>');
    }

    $(id).on('hidden.bs.modal', function () {
        if ($('.modal-backdrop').length < 1) {
            $(this).html('');
            $(this).data('modal', null);
        }
    });

    var content = '<div class="modal-dialog modal-lg">\
    <div class="modal-content">\
        <div class="modal-header">\
            <h5 class="modal-title">\
                <i class="fa fa-pencil"></i> ' + title + '\
            </h5>\
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                <span aria-hidden="true">&times;</span>\
            </button>\
        </div>\
        <div class="modal-body">|data|</div>\
    </div>\
</div>';

    // Lettura contenuto div
    if (href.substr(0, 1) == '#') {
        var data = $(href).html();

        $(id).html(content.replace("|data|", data));
        $(id).modal('show');
    } else {
        $.get(href, function (data, response) {
            if (response == 'success') {
                $(id).html(content.replace("|data|", data));
                $(id).modal('show');
            }
        });
    }
}