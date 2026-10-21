<?php
// ============================================================
// app/views/layouts/main.php — Layout principal Bootstrap 5
// ============================================================
// La variable $content est injectée par Controller::render()
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="BiblioNet — Système de gestion de bibliothèque en ligne" />
  <title><?= htmlspecialchars($pageTitle ?? 'BiblioNet') ?> — BiblioNet</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous" />

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

  <!-- Styles personnalisés -->
  <link href="<?= APP_URL ?>/assets/css/style.css" rel="stylesheet" />
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg navbar-dark bn-navbar fixed-top shadow-sm">
  <div class="container">
    <!-- Logo / Marque -->
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= APP_URL ?>/">
      <i class="bi bi-book-half fs-4"></i>
      <span>BiblioNet</span>
    </a>

    <!-- Toggle mobile -->
    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#mainNav"
            aria-controls="mainNav" aria-expanded="false" aria-label="Ouvrir le menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Liens de navigation -->
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="<?= APP_URL ?>/catalogue">
            <i class="bi bi-search me-1"></i>Catalogue
          </a>
        </li>

        <?php if (!empty($_SESSION['user_id'])): ?>
        <li class="nav-item">
          <a class="nav-link" href="<?= APP_URL ?>/livres">
            <i class="bi bi-journals me-1"></i>Livres
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= APP_URL ?>/adherents">
            <i class="bi bi-people me-1"></i>Adhérents
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= APP_URL ?>/emprunts">
            <i class="bi bi-arrow-left-right me-1"></i>Emprunts
          </a>
        </li>
        <?php endif; ?>
      </ul>

      <!-- Actions droite -->
      <div class="d-flex align-items-center gap-2">
        <?php if (!empty($_SESSION['user_id'])): ?>
          <span class="text-white-50 small d-none d-lg-inline">
            <i class="bi bi-person-circle me-1"></i>
            <?= htmlspecialchars($_SESSION['user_nom'] ?? 'Bibliothécaire') ?>
          </span>
          <form action="<?= APP_URL ?>/auth/logout" method="POST" class="m-0">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit" class="btn btn-outline-light btn-sm">
              <i class="bi bi-box-arrow-right me-1"></i>D&eacute;connexion
            </button>
          </form>
        <?php else: ?>
          <a href="<?= APP_URL ?>/auth/login" class="btn btn-light btn-sm">
            <i class="bi bi-box-arrow-in-right me-1"></i>Connexion
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<!-- ===== /NAVBAR ===== -->

<!-- ===== CONTENU PRINCIPAL ===== -->
<main class="bn-main">
  <!-- Messages flash -->
  <?php if (!empty($_SESSION['flash'])): ?>
    <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
      <div class="container mt-3">
        <div class="alert alert-<?= htmlspecialchars($type) ?> alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($msg) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
      </div>
    <?php endforeach; ?>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <!-- Vue courante -->
  <?= $content ?>
</main>
<!-- ===== /CONTENU PRINCIPAL ===== -->

<!-- ===== FOOTER ===== -->
<footer class="bn-footer text-center py-4 mt-auto">
  <div class="container">
    <p class="mb-0 text-white-50 small">
      &copy; <?= date('Y') ?> BiblioNet — Mini-projet MVC PHP
      <span class="mx-2">·</span>
      Charly OBAME
    </p>
  </div>
</footer>
<!-- ===== /FOOTER ===== -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmFMNkOHFnvIPEAhkr1h5Fuwkz1y"
        crossorigin="anonymous"></script>
</body>
</html>
