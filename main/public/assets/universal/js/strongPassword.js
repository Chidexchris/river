"use strict";

function secure_password(input) {
    let password = input.val();
    let capital = /[ABCDEFGHIJKLMNOPQRSTUVWXYZ]/;
    capital = capital.test(password);
    let capitalSelector = $('.capital')

    if (!capital) {
        capitalSelector.removeClass('success');
        capitalSelector.addClass('error');
    } else {
        capitalSelector.removeClass('error');
        capitalSelector.addClass('success');
    }

    let lower = /[abcdefghijklmnopqrstuvwxyz]/;
    lower = lower.test(password);
    let lowerSelector = $('.lower')

    if (!lower) {
        lowerSelector.removeClass('success');
        lowerSelector.addClass('error');
    } else {
        lowerSelector.removeClass('error');
        lowerSelector.addClass('success');
    }

    let number = /[1234567890]/;
    number = number.test(password);
    let numberSelector = $('.number')

    if (!number) {
        numberSelector.removeClass('success');
        numberSelector.addClass('error');
    } else {
        numberSelector.removeClass('error');
        numberSelector.addClass('success');
    }

    let special = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
    special = special.test(password);
    let specialSelector = $('.special')

    if (!special) {
        specialSelector.removeClass('success');
        specialSelector.addClass('error');
    } else {
        specialSelector.removeClass('error');
        specialSelector.addClass('success');
    }

    let minimum = password.length;
    let minimumSelector = $('.minimum')

    if (minimum < 6) {
        minimumSelector.removeClass('success');
        minimumSelector.addClass('error');
    } else {
        minimumSelector.removeClass('error');
        minimumSelector.addClass('success');
    }
}

(function ($) {
    let securePassword = $('.secure-password')

    securePassword.on('input', function () {
        secure_password($(this));
    });

    securePassword.on('focus', function () {
        $(this).closest('div').addClass('hover-input-popup');
    });

    securePassword.on('focusout', function () {
        $(this).closest('div').removeClass('hover-input-popup');
    });

    securePassword.closest('div').append(`<div class="input-popup">
                                                    <p class="error lower">1 small letter minimum</p>
                                                    <p class="error capital">1 capital letter minimum</p>
                                                    <p class="error number">1 number minimum</p>
                                                    <p class="error special">1 special character minimum</p>
                                                    <p class="error minimum">6 character password</p>
                                                </div>`);
})(jQuery);
