/* global chrome */
chrome.runtime.onMessage.addListener(
    function(request, sender, sendResponse) {
        if (request.action == "fetchLinkedInData") {
            const content = document.querySelector('main>section').innerText;

            sendResponse({content, location});
        }
    }
);
