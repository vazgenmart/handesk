$(document).ready(function () {

    $('.notification a').on('click', function (e) {
        var id = $(this).closest('tr').attr('id').split("_").reverse()[0];
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/remove_bell',
            data: {
                id: id
            },
            success: function (data) {

            }
        });

    })
});