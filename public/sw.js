"use strict"

self.addEventListener("notificationclick", (event) => {
    event.waitUntil(clients.openWindow(event.notification.actions[0].action));
});

self.addEventListener("install", function(event) {
    self.skipWaiting();
});

self.addEventListener("activate", function(event) {
    event.waitUntil(self.clients.claim());
});

self.addEventListener("push", function(event) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    const payload = event.data ? event.data.json() : {};
    event.waitUntil(self.registration.showNotification(payload.title, payload));
});
