
function getCookie(cname) {
	var name = cname + "=";
	var decodedCookie = decodeURIComponent(document.cookie);
	var ca = decodedCookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}

function setCookie(cname, cvalue, exdays = null) {
	var expires = '';
	if (exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
		expires = "expires=" + d.toUTCString() + ";";
	}
	document.cookie = cname + "=" + cvalue + ";" + expires + "path=/";
}

function kCopy(couponId, copyBtn) {
	navigator.clipboard.writeText(document.getElementById("code-" + couponId).innerText);
	if (copyBtn !== null) {
		copyBtn.innerHTML = '<i class="fa fa-check"></i>';
		copyBtn.style.color = '#45b145';
		setTimeout(function () {
			copyBtn.innerHTML = '<i class="fa fa-copy"></i>';
			copyBtn.style.color = '#808080';
		}, 1250);
	}
}
