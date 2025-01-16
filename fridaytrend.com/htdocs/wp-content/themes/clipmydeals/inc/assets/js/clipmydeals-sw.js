/* Service worker */
self.addEventListener('activate', () => { console.log('service worker activated') })

self.addEventListener('push', (event) => {
	if (!(self.Notification && self.Notification.permission === 'granted')) { return; }

	const sendNotification = body => {
		return self.registration.showNotification(body.title, {
			body: body.content,
			image: body.image,
			data: body.open_url, //the url which we gonna use later
		});
	};

	if (event.data) {
		const message = event.data.text();
		event.waitUntil(sendNotification(JSON.parse(message)));
	}
});

function isUrl(val) {
	try { return Boolean(new URL(val)); }
	catch (e) { return false; }
}

self.addEventListener('notificationclick', (event) => {
	console.log('On notification click: ', event.notification.tag);
	event.notification.close();
	var open_url = '/';

	if (event.notification.data && isUrl(event.notification.data)) open_url = event.notification.data;

	event.waitUntil(clients.matchAll({ type: "window" })
		.then(clientList => {
			for (var i = 0; i < clientList.length; i++) {
				var client = clientList[i];
				if (client.url == open_url && 'focus' in client) return client.focus();
			}
			if (clients.openWindow) { return clients.openWindow(open_url); }
		})
	);
});