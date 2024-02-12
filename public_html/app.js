if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('sw.js')
      .then((registration) => {
        console.log('Service Worker Registrado correctamente:', registration);
      })
      .catch((error) => {
        console.error('Service Worker a Fallado su Registro:', error);
      });
  }