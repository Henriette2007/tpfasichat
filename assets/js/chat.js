(function () {
    const API = 'api/messages.php';
    const POLL_INTERVAL_MS = 2000;
    const PSEUDO_KEY = 'tpfasi_pseudo';

    const loginPanel = document.getElementById('login-panel');
    const chatPanel = document.getElementById('chat-panel');
    const pseudoInput = document.getElementById('pseudo-input');
    const joinBtn = document.getElementById('join-btn');
    const leaveBtn = document.getElementById('leave-btn');
    const currentPseudoEl = document.getElementById('current-pseudo');
    const messagesEl = document.getElementById('messages');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const statusEl = document.getElementById('status');

    let pseudo = '';
    let lastMessageId = 0;
    let pollTimer = null;

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatTime(iso) {
        try {
            return new Date(iso).toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit',
            });
        } catch {
            return '';
        }
    }

    function setStatus(text, isError = false) {
        statusEl.textContent = text;
        statusEl.classList.toggle('error', isError);
    }

    function renderMessage(msg) {
        const li = document.createElement('li');
        li.className = 'message' + (msg.pseudo === pseudo ? ' mine' : '');
        li.dataset.id = String(msg.id);
        li.innerHTML =
            '<div class="message-header">' +
            '<strong>' + escapeHtml(msg.pseudo) + '</strong>' +
            '<span class="message-time">' + escapeHtml(formatTime(msg.created_at)) + '</span>' +
            '</div>' +
            '<div class="message-body">' + escapeHtml(msg.message) + '</div>';
        return li;
    }

    function appendMessages(messages) {
        let added = false;
        for (const msg of messages) {
            if (msg.id <= lastMessageId) continue;
            if (document.querySelector('[data-id="' + msg.id + '"]')) continue;
            messagesEl.appendChild(renderMessage(msg));
            lastMessageId = Math.max(lastMessageId, msg.id);
            added = true;
        }
        if (added) {
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }
    }

    async function fetchMessages(since = 0) {
        const url = since > 0 ? API + '?since=' + since : API;
        const res = await fetch(url);
        const data = await res.json();
        if (!res.ok) {
            throw new Error(data.error || 'Erreur réseau');
        }
        return data.messages || [];
    }

    async function poll() {
        try {
            const messages = await fetchMessages(lastMessageId);
            appendMessages(messages);
            setStatus('');
        } catch (err) {
            setStatus(err.message || 'Connexion perdue', true);
        }
    }

    function startPolling() {
        stopPolling();
        poll();
        pollTimer = setInterval(poll, POLL_INTERVAL_MS);
    }

    function stopPolling() {
        if (pollTimer) {
            clearInterval(pollTimer);
            pollTimer = null;
        }
    }

    async function join() {
        const value = pseudoInput.value.trim();
        if (!value) {
            setStatus('Entrez un pseudo.', true);
            return;
        }
        pseudo = value;
        sessionStorage.setItem(PSEUDO_KEY, pseudo);
        currentPseudoEl.textContent = pseudo;
        loginPanel.classList.add('hidden');
        chatPanel.classList.remove('hidden');
        messagesEl.innerHTML = '';
        lastMessageId = 0;
        setStatus('Chargement…');

        try {
            const messages = await fetchMessages(0);
            appendMessages(messages);
            setStatus('');
            startPolling();
            messageInput.focus();
        } catch (err) {
            setStatus(err.message || 'Impossible de charger le chat', true);
        }
    }

    function leave() {
        stopPolling();
        pseudo = '';
        sessionStorage.removeItem(PSEUDO_KEY);
        loginPanel.classList.remove('hidden');
        chatPanel.classList.add('hidden');
        messagesEl.innerHTML = '';
        lastMessageId = 0;
        setStatus('');
    }

    async function sendMessage(text) {
        const res = await fetch(API, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ pseudo, message: text }),
        });
        const data = await res.json();
        if (!res.ok) {
            throw new Error(data.error || 'Envoi impossible');
        }
        if (data.message) {
            appendMessages([data.message]);
        }
    }

    joinBtn.addEventListener('click', join);
    pseudoInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') join();
    });
    leaveBtn.addEventListener('click', leave);

    messageForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const text = messageInput.value.trim();
        if (!text) return;

        const submitBtn = messageForm.querySelector('button[type="submit"]');
        submitBtn.disabled = true;

        try {
            await sendMessage(text);
            messageInput.value = '';
            setStatus('');
        } catch (err) {
            setStatus(err.message || 'Erreur à l\'envoi', true);
        } finally {
            submitBtn.disabled = false;
            messageInput.focus();
        }
    });

    const saved = sessionStorage.getItem(PSEUDO_KEY);
    if (saved) {
        pseudoInput.value = saved;
        join();
    }
})();
