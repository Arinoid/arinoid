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

$(function () {
    if ($("#bookmark-table").length) {

        var json = {};

        $.get("site/get-bookmark", json)
            .done(function (data) {
                var obj = $.parseJSON(data);

                $.each(obj, function (key, val) {
                    console.log(val);

                    var div = $("<div>");

                    var grab_text = $("<div>");

                    grab_text.addClass('grab-text');

                    var a = $("<a>");
                    a.addClass('color-6-cyan').attr('style', 'float:left;display:block;width:250px;white-space: nowrap;overflow: hidden;').html(val.uri).attr('href', val.uri);

                    div.addClass().html(val.created_at + ' | ').append(a).append(grab_text);
                    $("#bookmark-table").append(div);
                });
            });

    }
});