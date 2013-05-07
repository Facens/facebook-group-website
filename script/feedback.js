$(document).ready(function(){

    // The relative URL of the submit.php script.
    // You will probably have to change it.
    var submitURL = 'submit.php';

    // Caching the feedback object:
    var feedback = $('#feedback');

    $('#feedback h6').click(function(){

        // We are storing the values of the animated
        // properties in a separate object:

        var anim    = {
            mb : 0,            // Margin Bottom
            pt : 25            // Padding Top
        };

        var el = $(this).find('.arrow');

        if(el.hasClass('down')){
            anim = {
                mb : -270,
                pt : 10
            };
        }

        // The first animation moves the form up or down, and the second one
        // moves the "Feedback" heading, so it fits in the minimized version

        feedback.stop().animate({marginBottom: anim.mb});

        feedback.find('.section').stop().animate({paddingTop:anim.pt},function(){
            el.toggleClass('down up');
        });
    });

    $('#feedback a.submit').live('click',function(){
        var button = $(this);
        var textarea = feedback.find('textarea');

        // We use the working class not only for styling the submit button,
        // but also as kind of a "lock" to prevent multiple submissions.

        if(button.hasClass('working') || textarea.val().length < 5){
            return false;
        }

        // Locking the form and changing the button style:
        button.addClass('working');

        $.ajax({
            url        : submitURL,
            type    : 'post',
            data    : { message : textarea.val()},
            complete    : function(xhr){

                var text = xhr.responseText;

                // This will help users troubleshoot their form:
                if(xhr.status == 404){
                    text = 'Your path to submit.php is incorrect.';
                }

                // Hiding the button and the textarea, after which
                // we are showing the received response from submit.php

                button.fadeOut();

                textarea.fadeOut(function(){
                    var span = $('<span>',{
                        className    : 'response',
                        html        : text
                    })
                    .hide()
                    .appendTo(feedback.find('.section'))
                    .show();
                }).val('');
            }
        });

        return false;
    });
});