//---------------- Create cookies popup by toggle button ----------------

jQuery(document).ready(function($) {

    if (!document.getElementById('theme-settings-form')) {
        // If the key element is not found, exit the script
        return;
    }

    // Function to toggle the visibility of the settings div
    function toggleSettingsDiv(enabled) {
        if (enabled) {
            $('#cookie-popup-settings').show();
        } else {
            $('#cookie-popup-settings').hide();
        }
    }

    // Check the current state of the cookie popup setting on page load
    $.ajax({
        url: cookiePopupData.ajax_url,
        type: 'post',
        data: {
            action: 'get_cookie_popup_setting',
            nonce: cookiePopupData.nonce
        },
        success: function(response) {
            if (response.success) {
                const enabled = response.data.enabled;
                $('#cookies_popup_enabled').prop('checked', enabled);
                toggleSettingsDiv(enabled);
            } else {
                console.log('Error fetching setting: ' + response.data.message);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('AJAX Error: ' + textStatus + ' : ' + errorThrown);
        }
    });

    // Toggle switch change event
    $('#cookies_popup_enabled').change(function() {
        var enabled = $(this).is(':checked') ? 'yes' : 'no';

        $.ajax({
            url: cookiePopupData.ajax_url,
            type: 'post',
            data: {
                action: 'update_cookie_popup_setting',
                nonce: cookiePopupData.nonce,
                enabled: enabled
            },
            success: function(response) {
                if (response.success) {
                    console.log('Setting updated successfully');
                    toggleSettingsDiv(enabled === 'yes');
                } else {
                    alert('Error: ' + response.data.message);
                    $('#cookies_popup_enabled').prop('checked', !$('#cookies_popup_enabled').is(':checked'));
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('AJAX Error: ' + textStatus + ' : ' + errorThrown);
                $('#cookies_popup_enabled').prop('checked', !$('#cookies_popup_enabled').is(':checked'));
            }
        });
    });
});
