// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts("https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js");
/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
*/
firebase.initializeApp({
    apiKey: "AIzaSyCM9PgU9J6hQYb8rQFr-e-p3uUQuHk0crU",
    authDomain: "permission-b04a6.firebaseapp.com",
    projectId: "permission-b04a6",
    storageBucket: "permission-b04a6.appspot.com",
    messagingSenderId: "830982844235",
    appId: "1:830982844235:web:9d6db3fa1ca1c6437f82ce",
    measurementId: "G-Q3HP3KQ21J",
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    console.log("Message received.", payload);
    const title = "Hello world is awesome";
    const options = {
        body: "Your notificaiton message .",
        icon: "/firebase-logo.png",
    };
    return self.registration.showNotification(title, options);
});
