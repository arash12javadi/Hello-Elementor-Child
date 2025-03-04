function validatePasswordStrength(input, charElem, capitalElem, numberElem, lengthElem, messageElem, submitButton) {
    var specialCharacters = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/g;
    var upperCaseLetters = /[A-Z]/g;
    var numbers = /[0-9]/g;

    charElem.classList.toggle("reg_psw_valid", input.value.match(specialCharacters));
    charElem.classList.toggle("reg_psw_invalid", !input.value.match(specialCharacters));

    capitalElem.classList.toggle("reg_psw_valid", input.value.match(upperCaseLetters));
    capitalElem.classList.toggle("reg_psw_invalid", !input.value.match(upperCaseLetters));

    numberElem.classList.toggle("reg_psw_valid", input.value.match(numbers));
    numberElem.classList.toggle("reg_psw_invalid", !input.value.match(numbers));

    lengthElem.classList.toggle("reg_psw_valid", input.value.length >= 8);
    lengthElem.classList.toggle("reg_psw_invalid", input.value.length < 8);

    var isValid =   charElem.classList.contains('reg_psw_valid') &&
                    capitalElem.classList.contains('reg_psw_valid') &&
                    numberElem.classList.contains('reg_psw_valid') &&
                    lengthElem.classList.contains('reg_psw_valid');

    messageElem.style.display = isValid ? "none" : "block";
    submitButton.disabled = !isValid;
}

//------------------------------------- Register Password Check ------------------------------------//

var AJDWP_myInput = document.getElementById("register_psw");
var Special_char = document.getElementById("Special_char");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");
var regMessage = document.getElementById("reg_psw_message");
var regSubmitButton = document.getElementById('reg_user_submit');

if (AJDWP_myInput) {
    AJDWP_myInput.onfocus = function() {
        regMessage.style.display = "block";
    }

    AJDWP_myInput.onblur = function() {
        regMessage.style.display = "none";
    }

    AJDWP_myInput.onkeyup = function() {
        validatePasswordStrength(AJDWP_myInput, Special_char, capital, number, length, regMessage, regSubmitButton);
    }
}


//------------------------------------- Change Password Check ------------------------------------//

var AJDWP_myInput_cp = document.getElementById("change_psw");
var Special_char_cp = document.getElementById("Special_char_cp");
var capital_cp = document.getElementById("capital_cp");
var number_cp = document.getElementById("number_cp");
var length_cp = document.getElementById("length_cp");
var cpMessage = document.getElementById("chg_psw_message");
var cpSubmitButton = document.getElementById('chg_psw_submit');

if (AJDWP_myInput_cp) {
    AJDWP_myInput_cp.onfocus = function() {
        cpMessage.style.display = "block";
    }

    AJDWP_myInput_cp.onblur = function() {
        cpMessage.style.display = "none";
    }

    AJDWP_myInput_cp.onkeyup = function() {
        validatePasswordStrength(AJDWP_myInput_cp, Special_char_cp, capital_cp, number_cp, length_cp, cpMessage, cpSubmitButton);
    }
}

//------------------------------------- Reset Password Form Check ------------------------------------//

var AJDWP_myInput_rpf = document.getElementById("new_password_reset");
var Special_char_rpf = document.getElementById("Special_char_rpf");
var capital_rpf = document.getElementById("capital_rpf");
var number_rpf = document.getElementById("number_rpf");
var length_rpf = document.getElementById("length_rpf");
var rpf_Message = document.getElementById("rpf_psw_message");
var rpf_SubmitButton = document.getElementById('new_password_reset_submit');

if (AJDWP_myInput_rpf) {
    AJDWP_myInput_rpf.onfocus = function() {
        rpf_Message.style.display = "block";
    }

    AJDWP_myInput_rpf.onblur = function() {
        rpf_Message.style.display = "none";
    }

    AJDWP_myInput_rpf.onkeyup = function() {
        validatePasswordStrength(AJDWP_myInput_rpf, Special_char_rpf, capital_rpf, number_rpf, length_rpf, rpf_Message, rpf_SubmitButton);
    }
}

//------------------------------------- media library access in edit profile popup------------------------------------//
jQuery(document).ready(function($){
    $('#open-media-library').on('click', function(e){
        e.preventDefault();

        var mediaFrame = wp.media({
            title: 'Select or Upload Media',
            button: {
                text: 'Use this media'
            },
            multiple: false
        });

        mediaFrame.on('select', function(){
            var attachment = mediaFrame.state().get('selection').first().toJSON();
            var imageUrl = attachment.url;

            $('#selected-image').attr('src', imageUrl);
            $('#selected-image-url').val(imageUrl);
        });

        mediaFrame.open();
    });
});
