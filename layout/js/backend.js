/* Function that hide placeholder and replace it again */
$(function (){
    'use strict';

    // Hide placeholder on form Focus
    //it doesn't work right now

    // Dashboard
    $('.toggle-info').click(function () {

        $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);

        if ($(this).hasClass('selected')){
           $(this).html('<i class="fa fa-minus fa-lg"></i>');
        }
    });

    $('[placeholder]').focus(function () {

        $(this).attr('data-text',$(this).attr('placeholder'));

        $(this).attr('placeholder','');

    }).blur(function () {

        $(this).attr('placeholder', $(this).attr('data-text'));

    });

    if ($(this).attr('required') === 'required') {

        $(this).after('<span class="asterisk">*</span>');
    }
    // convert password field to text on hover

    var passField = $('.password');
    $('.show-pass').hover(function () {

        passField.attr('type' , 'password');

    });

    // confirmation button
    $('.confirm').click( function()  {
        return confirm('Are You Sure ?');
    });



});






