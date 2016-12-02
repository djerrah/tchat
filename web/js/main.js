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

    function refreshContent() {
        var contentId = $('#tchat_content li').last().data('id');

        ($.ajax({
            type: "GET",
            url: '/refresh/' + contentId,
            data: {},
            success: function (data) {

                data = JSON.parse(data);

                refreshUsers(data['users']);
                appendMessages(data['messages']);

            },
            error: function () {
                console.log('error handing here');
            }
        }))
    }

    function refreshUsers(data) {
        if (data.length > 0) {
            $('.users').css('background-color', '#f4425c');

            jQuery.each(data, function (key, value) {

                var userId = value['id'];
                var color = '#f4425c';

                if (value['online'] == 1) {
                    color = '#419641';
                }

                $('.user-' + userId).css('background-color', color);
            });
        }
    }

    function appendMessages(data) {
        if (data.length > 0) {
            jQuery.each(data, function (key, value) {

                var messageId = value['message_id'];
                var userUsername = value['user_username'];

                var messageBody = jQuery('<div />').text(value['message_body']).html()
                var createdAt = value['message_created_at'];
                var userId = value['user_id'];
                var color = '#f4425c';

                if (value['user_online'] == 1) {
                    color = '#419641';
                }

                $('.user-' + userId).css('background-color', color);

                var li = '<li id="message_"' + messageId + ' data-id="' + messageId + '" title="' + createdAt + '"><span class="users user-' + userId + '" style="background-color: ' + color + '"><b>' + userUsername + '</b></span> : ' + messageBody + '</li>';
                $("#tchat_content").append(li);

                var $t = $("#tchat_content");
                $t.animate({"scrollTop": $("#tchat_content")[0].scrollHeight}, "slow");
            });
        }
    }

    $('#tchat_form').on('submit', function ($e) {
        $e.preventDefault();
        var datastring = $(this).serialize();
        var url = $(this).attr('action');

        post(url, datastring);
        $(this).find("#tchat_form_body").val('');
    });

    function refresh() {

        refreshContent();
        setTimeout(refresh, 8000);
        /* rappel après 2 secondes = 2000 millisecondes */
    }

    refresh();
});