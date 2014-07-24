$("#short-form").submit(function () {
    var uri = $("#uri");
    var json = {
        'uri': uri.val()
    };

    $("#main-response").hide();
    uri.addClass('loading background-color-4-turquoise');
    $.post('site/short', json,function () {

    }).done(function (data) {
        var response = $.parseJSON(data);
        var error = response.error;
        var site = response.site;
        var short_uri = response.uri;
        var qrcode = response.qrcode;

        uri.removeClass('loading background-color-4-turquoise');
        if (error == 0) {
            var full_uri = site + '/' + short_uri;
            var link = $("#short_uri");
            link.html(full_uri).attr('href', full_uri);
            $("#qrcode").attr('src', qrcode);
            $("#main-response").show();
            var subject = 'Short link from Arinoid.com: ' + full_uri;
            var body = 'Requested link: ' + $("#uri").val();
            $("#sendByEmail").attr('href', 'mailto:?Subject=' + subject + '&body=' + body);

            $(".copyToClipboard").click(function (e) {
                e.preventDefault();
            }).clipboard({
                path: '/js/jquery.clipboard.swf',
                copy: function () {
                    $(".copyToClipboard").addClass('copied').find("span").removeClass('glyphicon-briefcase').addClass('glyphicon-ok');

                    return $("#short_uri").html();
                }
            }).removeClass('copied').find("span").removeClass('glyphicon-ok').addClass('glyphicon-briefcase');

            var json = {
                'id': short_uri,
                'refresh': true
            };
            $.post('site/update-bookmark', json);

        } else if (error == 400) {
            alert('Bad request');
        }
    });
    return false;
});

$("#login").click(function () {
    $("#login-popup").show();
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

$("#bookmark-refresh").click(function () {
    $(this).addClass('active');

    if (refreshBookmarks()) {
        $(this).removeClass('active');
    }
});

var Event = function () {
    $(".toggleQrcode").click(function (e) {
        e.preventDefault();

        $("#qr" + $(this).data('value')).addClass('modal-show').show();
        $("#div").show();
    });

    $(".copyToClipboard").click(function (e) {
        e.preventDefault();
    }).clipboard({
        path: '/js/jquery.clipboard.swf',
        copy: function () {
            $(".copyToClipboard").removeClass('copied').find("span")
                .addClass('glyphicon-briefcase').removeClass('glyphicon-ok');
            $(this).addClass('copied').find("span")
                .removeClass('glyphicon-briefcase').addClass('glyphicon-ok');

            return $(this).data('value');
        }
    });

    $(".bookmark-element-description").click(function () {
        $(this).prev().addClass('modal-show').show();
        $("#div").show();
    });

    // Close modal window
    $("#div").click(function () {
        $(".modal-show").hide();

        $(this).hide();
    });

    $(".description").change(function () {
        var json = {
            'id': $(this).data('id'),
            'description': $(this).val()
        };

        $.post('site/update-bookmark', json,function () {

        }).done(function (data) {
            var response = $.parseJSON(data);
            var id = response.id;
            var description = response.description;

            if (id) {
                $("#div" + id).html(description).attr('value', description);
            }
        });
    });

    $(".remove").click(function () {
        var json = {
            'id': $(this).data('id'),
            'remove': true
        };

        $(this).addClass('active');
        $.post('site/update-bookmark', json,function () {

        }).done(function (data) {
            var response = $.parseJSON(data);
            var id = response.id;
            var remove = response.remove;

            if (remove) {
                $("#rm" + id).removeClass('active');
                $("#be" + id).remove();
            }
        });
    });

    $(".refresh").click(function () {
        var json = {
            'id': $(this).data('id'),
            'refresh': true
        };

        $(this).addClass('active');
        $.post('site/update-bookmark', json,function () {

        }).done(function (data) {
            var response = $.parseJSON(data);
            var id = response.id;
            var title = response.title;
            var thumbnail = response.thumbnail;

            if (title) {
                $("#rf" + id).removeClass('active');
                $("#ti" + id).html(title).attr('value', title);
            }

            if (thumbnail) {
                $("#th" + id).attr('src', thumbnail);
            }
        });
    });
};

function refreshBookmarks() {
    if ($("#bookmark-table").length) {

        var json = {};

        $.get("site/get-bookmark", json)
            .done(function (data) {
                var obj = $.parseJSON(data);

                $.each(obj.reverse(), function (key, val) {
                    if ($("#be" + val.uri_id).length == 0) {
                        var div = $("#bookmark-element").clone().attr('id', 'be' + val.uri_id);

                        div.find(".refresh").attr('id', 'rf' + val.uri_id).data('id', val.uri_id);
                        div.find(".remove").attr('id', 'rm' + val.uri_id).data('id', val.uri_id);

                        div.find(".bookmark-element-img").attr('id', 'th' + val.uri_id);
                        if (val.thumbnail) {
                            div.find(".bookmark-element-img").attr('src', val.thumbnail);
                        }

                        div.find(".bookmark-element-title").attr({'id': 'ti' + val.uri_id, 'title': val.title})
                            .html(val.title);
                        div.find(".bookmark-element-link").attr({'href': val.uri, 'title': val.uri}).html(val.uri);
                        div.find(".bookmark-element-date").html(val.date);

                        div.find(".bookmark-element-description")
                            .attr('id', 'div' + val.uri_id).html(val.description).attr('title', val.description);
                        div.find(".bookmark-element-description-div").find("textarea").addClass('description')
                            .attr('id', 'tex' + val.uri_id).data('id', val.uri_id).html(val.description);


                        div.find(".toggleQrcode").data('value', val.uri_id);
                        div.find(".bookmark-element-qrcode-div").attr('id', 'qr' + val.uri_id);
                        div.find(".bookmark-element-qrcode").attr('src', val.qrcode);

                        var full_uri = val.site + '/' + val.uri_id;
                        div.find(".bookmark-element-uri").attr('href', full_uri).html(full_uri);
                        div.find(".copyToClipboard").data('value', full_uri);

                        $("#bookmark-table").prepend(div);
                    }
                });

                Event();
            });

        return true;
    }

    return false;
}

$(function () {
    refreshBookmarks();
});