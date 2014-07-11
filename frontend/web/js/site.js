$("#short_form").submit(function () {
    var uri = $("#uri");
    var json = {
        'uri': uri.val()
    };

    $("#main-response").hide();
    uri.addClass('loading background-color-4-turquoise');
    $.post('site/short', json,function () {

    }).done(function (data) {
        var response = $.parseJSON(data);
        var site = response.site;
        var short_uri = response.uri;
        var qrcode = response.qrcode;

        uri.removeClass('loading background-color-4-turquoise');
        if (short_uri != null) {
            var full_uri = site + '/' + short_uri;
            $("#short_uri").html(full_uri).attr('href', full_uri);
            $("#qrcode").attr('src', qrcode);
            $("#main-response").show();
        }
    });
    return false;
});

$("#login").click(function () {
    $("#login-popup").css('left', '15%').show();
    $("#main-bookmark").hide();
    return false;
});

$("#login-popup-close").click(function () {
    $("#login-popup").hide();
    $("#main-bookmark").show();
});

$("#main-menu-popup").mouseover(function () {
    $(this).hide();
    $("#main-menu").show();
});

$("#main-menu").mouseleave(function () {
    $(this).hide();
    $("#main-menu-popup").show();
});

var Event = function () {
    $(".glyphicon-qrcode").click(function () {
        var div = $("#qr" + $(this).data('value'));

        div.toggle();
    });
}

$(function () {
    if ($("#bookmark-table").length) {

        var json = {};

        $.get("site/get-bookmark", json)
            .done(function (data) {
                var obj = $.parseJSON(data);

                $.each(obj, function (key, val) {
                    var div = $("#bookmark-element").clone().show();

                    div.find('.bookmark-element-title').html(val.title);
                    div.find('.bookmark-element-date').html(val.date);
                    div.find('.bookmark-element-link').attr('href', val.uri).html(val.uri);

                    div.find('.glyphicon-qrcode').data('value', val.uri_id);
                    div.find('.bookmark-element-qrcode-div').attr('id', 'qr' + val.uri_id);
                    div.find('.bookmark-element-qrcode').attr('src', val.qrcode);

                    var full_uri = val.site + '/' + val.uri_id;
                    div.find('.bookmark-element-uri').attr('href', full_uri).html(full_uri);

                    $("#bookmark-table").append(div);
                });

                Event();
            });

    }
});