jQuery(document).ready(function($) {
    $('a').filter(function() {
        return this.hostname && this.hostname !== location.hostname;
    }).click(function(e) {
        e.preventDefault(); // Prevent the default link behavior

        var externalUrl = $(this).attr('href');
        var openInNewTab = avLinkman.newTab; // Fetch this setting from localized script
        var popupText = avLinkman.popupText.replace('${DESTINATION_URL}', externalUrl); // Replace destination URL in the popup text

        var popup = $('<div>').attr('id', 'linkManagerPopup');
        var message = $('<p>').text(popupText);
        var continueButton = $('<button>').text(avLinkman.continueText || 'Continue');
        var cancelButton = $('<button>').text(avLinkman.cancelText || 'Cancel');

        continueButton.on('click', function() {
            popup.remove();
            if (openInNewTab) {
                window.open(externalUrl, '_blank'); // Open in new tab if setting is true
            } else {
                window.location.href = externalUrl; // Otherwise, open in the same tab
            }
        });

        cancelButton.on('click', function() {
            popup.remove();
        });

        popup.append(message, continueButton, cancelButton);
        $('body').append(popup);
    });
});