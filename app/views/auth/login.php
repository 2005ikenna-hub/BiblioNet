<div class="container py-4">
  <div class="card bn-form-card shadow-sm">
    <div class="card-body p-4 p-md-5">
      <h1 class="h3 mb-4 text-center"><i class="bi bi-box-arrow-in-right me-2"></i>Connexion bibliothécaire</h1>
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>
      <form action="<?= APP_URL ?>/auth/login/submit" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
        <div class="mb-3">
          <label for="email" class="form-label">Adresse e-mail</label>
          <input type="email" class="form-control" id="email" name="email" required maxlength="150" autocomplete="username" value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="mb-4">
          <label for="password" class="form-label">Mot de passe</label>
          <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
        </div>
        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
      </form>
    </div>
  </div>
</div>
