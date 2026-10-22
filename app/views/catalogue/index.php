<?php
// ============================================================
// app/views/catalogue/index.php — Page publique du catalogue
// ============================================================
?>

<!-- HERO -->
<section class="bn-hero">
  <div class="container text-center">
    <h1 class="fade-in-up">
      <i class="bi bi-book-half me-2"></i>Catalogue de la bibliothèque
    </h1>
    <p class="fade-in-up" style="animation-delay:.1s">
      Recherchez parmi nos ouvrages disponibles en temps réel
    </p>

    <!-- Barre de recherche -->
    <form action="<?= APP_URL ?>/catalogue/recherche" method="GET"
          class="d-flex justify-content-center mt-4 fade-in-up"
          style="max-width:600px; margin:0 auto; animation-delay:.2s">
      <input type="text"
             id="catalogue-search"
             name="q"
             value="<?= htmlspecialchars($query) ?>"
             class="form-control bn-search-input"
             placeholder="Titre, auteur…"
             autocomplete="off" />
      <button type="submit" class="btn bn-search-btn">
        <i class="bi bi-search me-1"></i>Rechercher
      </button>
    </form>
  </div>
</section>

<!-- RÉSULTATS -->
<div class="container">

  <?php if (!empty($query)): ?>
    <p class="text-muted mb-3">
      <i class="bi bi-funnel me-1"></i>
      <?= count($livres) ?> résultat(s) pour <strong>« <?= htmlspecialchars($query) ?> »</strong>
      — <a href="<?= APP_URL ?>/catalogue">Réinitialiser</a>
    </p>
  <?php endif; ?>

  <?php if (empty($livres)): ?>
    <div class="text-center py-5">
      <i class="bi bi-search fs-1 text-muted"></i>
      <p class="mt-3 text-muted">Aucun livre trouvé.</p>
    </div>
  <?php else: ?>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
      <?php foreach ($livres as $i => $livre): ?>
        <?php $dispo = (int)($livre['disponibles'] ?? $livre['nb_exemplaires']); ?>

        <div class="col fade-in-up" style="animation-delay: <?= $i * 0.04 ?>s">
          <div class="bn-card card h-100 p-0">
            <?php if (!empty($livre['image_path'])): ?>
              <img src="<?= APP_URL ?>/<?= htmlspecialchars($livre['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="Couverture de <?= htmlspecialchars($livre['titre'], ENT_QUOTES, 'UTF-8') ?>" class="bn-catalogue-cover" data-fallback-tag="div" data-fallback-class="bn-catalogue-cover bn-catalogue-placeholder" data-fallback-icon="bi bi-book-half">
            <?php else: ?>
              <div class="bn-catalogue-cover bn-catalogue-placeholder" aria-hidden="true"><i class="bi bi-book-half"></i></div>
            <?php endif; ?>
            <!-- En-tête coloré selon disponibilité -->
            <div class="card-header text-white fw-semibold rounded-top"
                 style="background: <?= $dispo > 0 ? 'linear-gradient(135deg,#1a3c5e,#2980b9)' : 'linear-gradient(135deg,#7f8c8d,#95a5a6)' ?>">
              <small><?= htmlspecialchars($livre['categorie'] ?: 'Divers') ?></small>
            </div>

            <div class="card-body d-flex flex-column p-3">
              <h5 class="card-title fw-bold mb-1" style="font-size:.95rem">
                <?= htmlspecialchars($livre['titre']) ?>
              </h5>
              <p class="text-muted small mb-1">
                <i class="bi bi-person me-1"></i><?= htmlspecialchars($livre['auteur']) ?>
              </p>
              <p class="text-muted small mb-3">
                <i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars($livre['annee']) ?>
              </p>

              <div class="mt-auto d-flex align-items-center justify-content-between">
                <span class="badge <?= $dispo > 0 ? 'badge-dispo' : 'badge-indispo' ?> rounded-pill px-3 py-2">
                  <?= $dispo > 0 ? "✓ Disponible ($dispo ex.)" : '✗ Indisponible' ?>
                </span>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  <?php endif; ?>

</div>
