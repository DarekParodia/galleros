$(document).ready(function() {
    $.ajax({
        url: './api/logout.php',
        method: 'GET'
    })
    .always(function() {
        // Delete all cookies
        var cookies = document.cookie.split(";");
        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i];
            var eqPos = cookie.indexOf("=");
            var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
            document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
        }
        window.location.href = './';
    });
}
);
