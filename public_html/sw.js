// Asignar un nombre y versión al caché
const CACHE_NAME = 'operamaq-v1';
const urlsToCache = [
    './',
    './logInsp.php',
    './sw.js',
    './manifest.json',
    './app.js'

  // Agrega todas las rutas de tus recursos estáticos aquí
];

self.addEventListener('install', e => {
  e.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache)
          .then(() => self.skipWaiting());
      })
      .catch(err => console.log('Falló el registro de caché', err))
  );
});

self.addEventListener('activate', e => {
  const cacheWhitelist = [CACHE_NAME];

  e.waitUntil(
    caches.keys()
      .then(cacheNames => {
        return Promise.all(
          cacheNames.map(cacheName => {
            if (cacheWhitelist.indexOf(cacheName) === -1) {
              return caches.delete(cacheName);
            }
          })
        );
      })
      .then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', e => {
  e.respondWith(
    caches.match(e.request)
      .then(res => {
        if (res) {
          return res;
        }

        return fetch(e.request)
          .then(response => {
            if (!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }

            const responseToCache = response.clone();

            caches.open(CACHE_NAME)
              .then(cache => {
                cache.put(e.request, responseToCache);
              });

            return response;
          })
          .catch(() => {
            return caches.match('./offline.html');
          });
      })
  );
});

self.addEventListener('notificationclick', e => {
  const url = e.notification.data.url;

  e.notification.close();

  e.waitUntil(
    clients.matchAll({ type: 'window' })
      .then(clientList => {
        for (let i = 0; i < clientList.length; i++) {
          const client = clientList[i];
          if ('focus' in client) {
            return client.focus();
          }
        }
        if (clients.openWindow) {
          return clients.openWindow(url);
        }
      })
  );
});

self.addEventListener('push', e => {
  const data = JSON.parse(e.data.text());

  console.log(data);

  const title = data.title;
  const options = {
    body: data.body,
    icon: data.icon,
    image: data.image,
    badge: data.badge,
    vibrate: [100, 50, 100],
    data: {
      url: data.url
    },
    actions: [
      { action: 'Si', title: 'Si', icon: 'https://i.imgur.com/4X8mHt3.png' },
      { action: 'No', title: 'No', icon: 'https://i.imgur.com/4X8mHt3.png' }
    ]
  };

  e.waitUntil(self.registration.showNotification(title, options));
});