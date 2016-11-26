/**
 * Created by djerrah on 26/11/16.
 */

jQuery(document).ready(function ($) {

    console.log('ssds');

    function post(url, data)
    {
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function (data) {
                $( "#tchat_content" ).html( data );
            },
            error: function () {
                alert('error handing here');
            }
        })
    }

    function getContent()
    {
        $.ajax({
            type: "POST",
            url: '/tchat',
            data: {},
            success: function (data) {
                $( "#tchat_content" ).html( data );
            },
            error: function () {
                alert('error handing here');
            }
        })
    }

    $('#tchat_form').on('submit', function($e){
        var datastring = $(this).serialize();
        var url =  $(this).attr('action');

        post(url, datastring);

        $(this).find("#tchat_form_body").val('');

        $e.preventDefault();
    });

    function tafonction(){

        getContent();
        setTimeout(tafonction,2000); /* rappel après 2 secondes = 2000 millisecondes */
    }

    tafonction();
});