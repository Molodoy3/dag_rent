<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">

    @routes
    @vite(['resources/js/app.js', 'resources/css/app.scss'])
    @inertiaHead
</head>
<body>
@inertia

<script>
    //ниже все для уведомлений
    //подкл к уведомлениям и тестовая отправка через js
    //<button id="notificationsOn">Notify me!</button>
    function notifyMe() {
        if (!("Notification" in window)) {
            // Check if the browser supports notifications
            alert("This browser does not support desktop notification");
        } else if (Notification.permission === "granted") {
            // Check whether notification permissions have already been granted;
            // if so, create a notification
            const notification = new Notification("Hi there!");
            // …
        } else if (Notification.permission !== "denied") {
            // We need to ask the user for permission
            Notification.requestPermission().then((permission) => {
                // If the user accepts, let's create a notification
                if (permission === "granted") {
                    const notification = new Notification("Hi there!");
                    // …
                }
            });}}

    document.addEventListener('click', (e) => {
        const targetElement = e.target;
        //уведомления
        if (targetElement.closest("#enable-push")) {
            targetElement.closest("#enable-push").classList.add("lock");
            enablePushNotifications();
        }
        if (targetElement.closest("#disable-push")) {
            targetElement.closest("#disable-push").classList.add("lock");
            disablePushNotifications();
        }
        if (targetElement.closest("#notificationsOn")) {
            notifyMe()
            //targetElement.closest("#notificationsOn").classList.add("lock");
        }
    });


    const VAPID_PUBLIC_KEY = 'BGum-fulDrniTxeaHoXkzM4HwpuxHoV4gAn60oDyVTROxSfw16WL16ATLy84mF0a-Nm8tv3SI5_jG2skEbIeD9o';
    const csrftoken = document.querySelector('meta[name="csrf-token"]').getAttribute('Content');
    if ("serviceWorker" in navigator) {
        window.addEventListener("load", function() {
            navigator.serviceWorker.register("/sw.js");
        });
    }

    function urlBase64ToUint8Array(base64String) {
        const padding = "=".repeat((4 - (base64String.length % 4)) % 4);
        const base64 = (base64String + padding).replace(/\-/g, "+").replace(/_/g, "/");

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }

        return outputArray;
    }
    function subscribe(sub) {
        if (!("Notification" in window)) {
            alert("Этот браузер не поддерживает уведомления:(");
        } else {
            //пытаемся включить увед, если не включены
            if (Notification.permission !== "denied") {
                Notification.requestPermission().then((permission) => {
                    /*if (permission === "granted") {
                        const notification = new Notification("Hi there!");
                    }*/
                });}

            //далее уже подписываем пользователя на уведомление(отправляем post запрос)
            const key = sub.getKey('p256dh')
            const token = sub.getKey('auth')
            const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0]

            const data = {
                endpoint: sub.endpoint,
                public_key: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
                auth_token: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
                encoding: contentEncoding,
            };

            fetch('/notifications/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrftoken
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    createMessageBlock("Вы подписались на уведомления!")
                })
                .catch((error) => {
                    createMessageBlock("Произошла ошибка!")
                });
            }
    }

    function createMessageBlock(message) {
        const profileConfidential = document.querySelector('.profile__confidential');
        if (profileConfidential) {
            const oldMessage = document.querySelector('.message')
            if (oldMessage)
                oldMessage.remove();
            const messageBlock = document.createElement('div');
            messageBlock.classList.add('message');
            const deleteButton = document.createElement('button');
            deleteButton.classList.add('button-delete-message');
            deleteButton.textContent = 'X';
            const messageText = document.createTextNode(message);
            messageBlock.appendChild(deleteButton);
            messageBlock.appendChild(messageText);
            profileConfidential.prepend(messageBlock);
        }
    }



    function enablePushNotifications() {
        navigator.serviceWorker.ready.then(registration => {
            registration.pushManager.getSubscription().then(subscription => {
                if (subscription) {
                    return subscription;
                }

                const serverKey = urlBase64ToUint8Array(VAPID_PUBLIC_KEY);

                return registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: serverKey
                });
            }).then(subscription => {
                if (!subscription) {
                    alert('Error occured while subscribing');
                    return;
                }
                subscribe(subscription);
            });
        });
    }
    function disablePushNotifications() {
        navigator.serviceWorker.ready.then(registration => {
            registration.pushManager.getSubscription().then(subscription => {
                if (!subscription) {
                    return;
                }

                subscription.unsubscribe().then(() => {
                    fetch('/notifications/unsubscribe', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrftoken
                        },
                        body: JSON.stringify({
                            endpoint: subscription.endpoint
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            createMessageBlock("Вы отписались от уведомлений!")
                        })
                        .catch((error) => {
                            createMessageBlock("Произошла ошибка!")
                        });
                })
            });
        });
    }
    /*function requestPermission() {
        return new Promise(function(resolve, reject) {
            const permissionResult = Notification.requestPermission(function(result) {
                // Поддержка устаревшей версии с функцией обратного вызова.
                resolve(result);
            });

            if (permissionResult) {
                permissionResult.then(resolve, reject);
            }
        })
            .then(function(permissionResult) {
                if (permissionResult !== 'granted') {
                    throw new Error('Permission not granted.');
                }
            });
    }*/
    //requestPermission();
</script>
</body>
</html>
