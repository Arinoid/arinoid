$("#short_form").on('submit', function () {
    var uri = $('#uri');
    var json = {
        'uri': uri.val()
    };

    uri.addClass('loading background-color-turquoise');
    $.post('site/short', json,function () {

    }).done(function (data) {
        var response = $.parseJSON(data);
        var site = response.site;
        var short_uri = response.uri;

        uri.removeClass('loading background-color-turquoise');
        if (short_uri != null) {
            var full_uri = site + '/' + short_uri;
            $('#short_uri').html(full_uri).attr('href', full_uri);
        }
    });
    return false;
});