<?php
declare(strict_types=1);

session_start();
$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FasiChat Classroom - Creer un compte</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="assets/login.css">
<style>
  .register-box { max-width: 520px; margin: 40px auto; background: rgba(255,255,255,.92); border-radius: 16px; padding: 24px; }
  .register-title { margin: 0 0 6px; font-size: 26px; font-weight: 700; }
  .register-sub { margin: 0 0 18px; color: #4b5563; font-size: 14px; }
  .alert { padding: 10px 12px; border-radius: 10px; margin-bottom: 14px; font-size: 14px; }
  .alert.error { background: #fee2e2; color: #991b1b; }
  .alert.success { background: #dcfce7; color: #166534; }
</style>
</head>
<body>
<div class="bg-layer"></div>
<div class="grid-lines"></div>
<div class="orb orb-1"></div>

<div class="register-box">
  <h1 class="register-title">Creer un compte</h1>
  <p class="register-sub">Renseignez vos informations pour acceder a FasiChat.</p>

  <?php if ($error !== ''): ?>
    <div class="alert error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php if ($success !== ''): ?>
    <div class="alert success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form action="register_process.php" method="post">
    <div class="form-group">
      <label class="form-label">Nom complet</label>
      <div class="input-wrapper">
        <span class="input-icon"><i class="fa-regular fa-user"></i></span>
        <input type="text" name="nom" class="form-input" required>
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">Identifiant</label>
      <div class="input-wrapper">
        <span class="input-icon"><i class="fa-solid fa-id-card"></i></span>
        <input type="text" name="identifiant" class="form-input" required>
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">Role</label>
      <div class="input-wrapper">
        <span class="input-icon"><i class="fa-solid fa-user-tag"></i></span>
        <select name="role" class="form-input" required>
          <option value="etudiant">Etudiant</option>
          <option value="enseignant">Enseignant</option>
          <option value="apparitaire">Assistant</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">Mot de passe</label>
      <div class="input-wrapper">
        <span class="input-icon"><i class="fa-solid fa-key"></i></span>
        <input type="password" name="password" class="form-input" minlength="6" required>
      </div>
    </div>

    <button type="submit" class="btn-login">Creer mon compte</button>
  </form>

  <div class="register-link" style="margin-top:16px;">
    Deja inscrit ? <a href="login.html">Se connecter</a>
  </div>
</div>
</body>
</html>
