jQuery(document).ready(function($) {
    $('a').filter(function() {
        return this.hostname && this.hostname !== location.hostname;
    }).click(function(e) {
        e.preventDefault(); // Prevent the default link behavior

        var externalUrl = $(this).attr('href');
        var openInNewTab = wpLinkman.newTab;
        var popupText = wpLinkman.popupText.replace('${DESTINATION_URL}', externalUrl); // Replace destination URL in the popup text

        var popup = $('<div>').attr('id', 'linkManagerPopup');
        var message = $('<p>').text(popupText);
        // Access the localized button texts
        var continueButtonText = wpLinkman.continueText || 'Continue';
        var cancelButtonText = wpLinkman.cancelText || 'Cancel';

        var continueButton = $('<button>').text(continueButtonText);
        var cancelButton = $('<button>').text(cancelButtonText);


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
