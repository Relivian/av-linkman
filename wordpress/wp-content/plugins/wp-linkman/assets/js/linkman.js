jQuery(document).ready(function($) {
    $('a').filter(function() {
        // Filter out internal links and ensure the link is an actual URL
        return this.hostname && this.hostname !== location.hostname;
    }).click(function(e) {
        e.preventDefault(); // Prevent the default link behavior

        var externalUrl = $(this).attr('href');
        var openInNewTab = wpLinkman.newTab;  // Access the newTab property from localized script data
        var popupText = wpLinkman.popupText.replace('${DESTINATION_URL}', externalUrl); // Replace destination URL in the popup text

        // Create the popup with ID and append content
        var popup = $('<div>').attr('id', 'linkManagerPopup');
        var message = $('<p>').text(popupText);
        var continueButton = $('<button>').text('Continue');
        var cancelButton = $('<button>').text('Cancel');

        continueButton.on('click', function() {
            popup.remove();
            if (openInNewTab) {
                window.open(externalUrl, '_blank');
            } else {
                window.location.href = externalUrl;
            }
        });

        cancelButton.on('click', function() {
            popup.remove();
        });

        popup.append(message, continueButton, cancelButton);
        $('body').append(popup);
    });
});
