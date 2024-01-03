chrome.cookies.get({url: "localhost", name: "session_cookie_name"}, function(cookie) {
    console.log(cookie.value);
});
