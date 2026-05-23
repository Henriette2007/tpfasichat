# tpfasichat

Chat web simple en **PHP** (TP Fasi) : interface HTML/CSS/JS, API REST, messages stockés en JSON.

## Prérequis

- PHP 8.0 ou plus (`php -v`)

## Lancer le projet

À la racine du dépôt :

```bash
php -S localhost:8000
```

Ouvrez [http://localhost:8000](http://localhost:8000) dans le navigateur.

## Structure

| Fichier | Rôle |
|---------|------|
| `index.php` | Page du chat |
| `config.php` | Configuration et dossier `data/` |
| `api/messages.php` | API : `GET` (lire), `POST` (envoyer) |
| `data/messages.json` | Historique des messages (créé au premier envoi) |
| `assets/css/style.css` | Styles |
| `assets/js/chat.js` | Pseudo, envoi, rafraîchissement toutes les 2 s |

## API

**GET** `api/messages.php` — tous les messages  
**GET** `api/messages.php?since=5` — messages avec `id` > 5  

**POST** `api/messages.php` — corps JSON :

```json
{ "pseudo": "Alice", "message": "Bonjour !" }
```

## Fonctionnalités

- Pseudo obligatoire (mémorisé en session navigateur)
- Messages en temps quasi réel (polling)
- Limite : 32 caractères pour le pseudo, 500 pour le message
- Conservation des 200 derniers messages
