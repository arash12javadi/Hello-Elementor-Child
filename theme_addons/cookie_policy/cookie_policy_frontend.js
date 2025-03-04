jQuery(document).ready(function($) {
    // Check if the user has already accepted cookies
    if (getCookie('cookiesAccepted') !== 'yes') {
        $('#cookieModal').modal('show');
    }

    // Handle the accept button click
    $('#acceptCookies').on('click', function() {
        // Set the cookie to expire in one year
        setCookie('cookiesAccepted', 'yes', 365);
        $('#cookieModal').modal('hide');
    });
});

// JavaScript functions to manage cookies
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}
