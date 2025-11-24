if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/PWA/service-worker.js')
            .then(registration => {
                console.log('Service Worker registado com sucesso:', registration.scope);
            })
            .catch(error => {
                console.log('Falha ao registar o Service Worker:', error);
            });
    });
}

let deferredPrompt;
const installBtn = document.getElementById('installBtn');

if (installBtn) {
    installBtn.style.display = 'none';
}

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;

    if (installBtn) {
        installBtn.style.display = 'block';
        
        installBtn.addEventListener('click', () => {
            installBtn.style.display = 'none';

            deferredPrompt.prompt();

            deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    console.log('Utilizador aceitou instalar a app');
                } else {
                    console.log('Utilizador recusou instalar a app');
                }
                deferredPrompt = null;
            });
        });
    }
});