function toggleFields(field) {
    var excerptFields           = document.getElementById('excerpt_fields');
    var loginPageField          = document.getElementById('login_page_field');
    var diskUsageLimitField     = document.getElementById('user_upload_settings');
    var googleTagManagerField     = document.getElementById('google_tag_manager_field');

    if (field === 'custom_excerpt_length') {
        excerptFields.style.display = document.getElementById(field).checked ? 'block' : 'none';
    }
    if (field === 'redirect_login_page') {
        loginPageField.style.display = document.getElementById(field).checked ? 'block' : 'none';
    }
    if (field === 'limit_uploads') {
        diskUsageLimitField.style.display = document.getElementById(field).checked ? 'block' : 'none';
    }
    if (field === 'google_tag_manager') {
        googleTagManagerField.style.display = document.getElementById(field).checked ? 'block' : 'none';
    }

}

// Initialize field visibility based on existing settings
document.addEventListener('DOMContentLoaded', function() {

    if (!document.getElementById('theme-settings-form')) {
        // If the key element is not found, exit the script
        return;
    }

    var fields = ['custom_excerpt_length', 'redirect_login_page', 'limit_uploads', 'google_tag_manager'];
    fields.forEach(function(field) {
        toggleFields(field);
    });

    var roleDifferenceElement = document.getElementById('roleDifference');
    if (roleDifferenceElement) {
        roleDifferenceElement.addEventListener('click', function() {
            
            var RoleComparisonTablePath = RoleComparisonTable.htmlFilePath;
            
            // Fetch the content from the external HTML file
            fetch(RoleComparisonTablePath)
                .then(response => response.text())
                .then(data => {
                    showPopup(data);
                })
                // .catch(error => console.error('Error loading roles comparison:', error));
        });
    }

});


function showPopup(message) {
    // Remove any existing popups
    var existingPopup = document.getElementById('custom-popup');
    if (existingPopup) {
        existingPopup.remove();
    }

    // Create new popup
    var popup = document.createElement('div');
    popup.id = 'custom-popup';

    var contentElement = document.createElement('div');
    contentElement.innerHTML = message;
    popup.appendChild(contentElement);

    var closeButton = document.createElement('button');
    closeButton.textContent = 'Close';
    closeButton.id = 'custom-popup-close';
    closeButton.onclick = function() {
        popup.remove(); // Remove the popup from the DOM
    };
    popup.appendChild(closeButton);

    document.body.appendChild(popup);
}



document.addEventListener('DOMContentLoaded', function() {

    if (!document.getElementById('theme-settings-form')) {
        // If the key element is not found, exit the script
        return;
    }
    
    const form = document.getElementById('theme-settings-form');
    const container = document.getElementById('user_disk_space_container');
    const addButton = document.getElementById('add_user_disk_space');

    if (!form || !container || !addButton) {
        console.error('Required elements are missing in the DOM.');
        return;
    }

    // Function to get duplicate emails
    function getDuplicateEmails() {
        const emailInputs = container.querySelectorAll('input[type="email"]');
        const emailCounts = {};
        const duplicates = [];

        emailInputs.forEach(input => {
            const email = input.value.trim().toLowerCase();
            if (email) {
                emailCounts[email] = (emailCounts[email] || 0) + 1;
            }
        });

        for (const [email, count] of Object.entries(emailCounts)) {
            if (count > 1) {
                duplicates.push(email);
            }
        }

        return duplicates;
    }

    // Function to handle form submission
    function handleFormSubmit(event) {
        const duplicates = getDuplicateEmails();
        if (duplicates.length > 0) {
            alert('Duplicate email addresses found: ' + duplicates.join(', '));
            event.preventDefault(); // Prevent form submission
        }
    }

    // Function to check for duplicates and update Disk Space fields
    function checkDuplicateEmails() {
        const emailInputs = Array.from(container.querySelectorAll('input[type="email"]'));
        const diskSpaceInputs = Array.from(container.querySelectorAll('input[type="number"]'));
        const uniqueEmails = new Set();

        emailInputs.forEach((emailInput, index) => {
            const email = emailInput.value.trim().toLowerCase();
            const diskSpaceInput = diskSpaceInputs[index];

            if (uniqueEmails.has(email)) {
                diskSpaceInput.disabled = true;  // Disable Disk Space field
            } else {
                diskSpaceInput.disabled = false; // Enable Disk Space field
                uniqueEmails.add(email); // Add email to the set
            }
        });
    }

    // Initial check on page load
    checkDuplicateEmails();

    // Event listener for form submission
    form.addEventListener('submit', handleFormSubmit);

    // Event listener for adding a new user
    addButton.addEventListener('click', () => {
        const rowIndex = container.querySelectorAll('.user-disk-space-row').length;
        const newRow = document.createElement('div');
        newRow.className = 'user-disk-space-row';

        newRow.innerHTML = `
            <label for="user_email_${rowIndex}">User Email:</label>
            <input type="email" id="user_email_${rowIndex}" name="AJDWP_theme_options[entered_email_for_disk_usage_limit][]" required>
            <label for="disk_space_${rowIndex}">Disk Space (MB):</label>
            <input type="number" id="disk_space_${rowIndex}" name="AJDWP_theme_options[entered_amount_for_disk_usage_limit][]" min="0" required>
            <button type="button" class="remove-row">Remove</button>
        `;

        container.appendChild(newRow);

        // Re-check emails and disk space fields after adding a new row
        checkDuplicateEmails();

        // Add event listener to the new row's remove button
        newRow.querySelector('.remove-row').addEventListener('click', function() {
            container.removeChild(newRow);
            // Re-check emails and disk space fields after removing a row
            checkDuplicateEmails();
        });
    });

    // Event delegation for existing and new remove buttons
    container.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-row')) {
            const row = event.target.closest('.user-disk-space-row');
            if (row) {
                container.removeChild(row);
                // Re-check emails and disk space fields after removing a row
                checkDuplicateEmails();
            }
        }
    });

    // Event listener to check for duplicate emails on input change
    container.addEventListener('input', (event) => {
        if (event.target.type === 'email') {
            checkDuplicateEmails();
        }
    });
});
