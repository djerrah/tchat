/**
 * Created by djerrah on 26/11/16.
 */

jQuery(document).ready(function ($) {

    function post(url, data) {
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function (data) {
                $("#tchat_content").html(data);
            },
            error: function () {
                console.log('error handing here');
            }
        })
    }

    function getContent() {
        var contentId = $('#tchat_content li').last().data('id');

        ($.ajax({
            type: "GET",
            url: '/refresh/' + contentId,
            data: {},
            success: function (data) {

                data = JSON.parse(data);

                if (data.length > 0) {
                    jQuery.each(data, function (key, value) {

                        var messageId = value['message_id'];
                        var userUsername = value['user_username'];

                        var messageBody = jQuery('<div />').text(value['message_body']).html()

                        var createdAt = value['message_created_at'];

                        var color = '#419643';

                        if (value['user_online'] == 1) {
                            color = '#419641';
                        }

                        var li = '<li id="message_"' + messageId + ' data-id="' + messageId + '" title="' + createdAt + '"><span style="background-color: ' + color + '"><b>' + userUsername + '</b></span> : ' + messageBody + '</li>';
                        $("#tchat_content").append(li);

                        var $t = $("#tchat_content");
                        $t.animate({"scrollTop": $("#tchat_content")[0].scrollHeight}, "slow");
                    });
                }
            },
            error: function () {
                console.log('error handing here');
            }
        }))
    }

    $('#tchat_form').on('submit', function ($e) {
        var datastring = $(this).serialize();
        var url = $(this).attr('action');

        post(url, datastring);

        $(this).find("#tchat_form_body").val('');

        $e.preventDefault();
    });

    function tafonction() {

        getContent();
        setTimeout(tafonction, 8000);
        /* rappel après 2 secondes = 2000 millisecondes */
    }

    tafonction();
});