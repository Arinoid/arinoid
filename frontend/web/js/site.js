$("#short_form").on('submit', function () {
    var json = {
        'uri': $('#uri').val()
    };
    $.post('site/short', json,function () {

    }).done(function (data) {
        var response = $.parseJSON(data);
        var site = response.site;
        var short_uri = response.uri;

        if (short_uri != null) {
            var full_uri = site + '/' + short_uri;
            $('#short_uri').html(full_uri).attr('href', full_uri);
        }
    });
    return false;
});