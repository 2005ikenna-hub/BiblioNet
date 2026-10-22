<div class="container py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div><h1 class="h2 mb-1">Gestion des livres</h1><p class="text-muted mb-0"><?= count($livres) ?> livre(s) dans le catalogue</p></div>
    <a href="<?= APP_URL ?>/livres/creer" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Ajouter un livre</a>
  </div>
  <?php if (!$livres): ?>
    <div class="alert alert-info">Aucun livre enregistré. Ajoutez le premier livre pour commencer.</div>
  <?php else: ?>
    <div class="card shadow-sm"><div class="table-responsive"><table class="table table-hover align-middle mb-0 bn-table">
      <thead><tr><th>Image</th><th>Titre</th><th>Auteur</th><th>Catégorie</th><th>Année</th><th>Exemplaires</th><th class="text-end">Actions</th></tr></thead>
      <tbody>
        <?php foreach ($livres as $livre): ?>
          <tr>
            <td><?php if (!empty($livre['image_path'])): ?><img src="<?= APP_URL ?>/<?= htmlspecialchars($livre['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="Couverture de <?= htmlspecialchars($livre['titre'], ENT_QUOTES, 'UTF-8') ?>" class="bn-table-thumbnail"><?php else: ?><span class="bn-table-placeholder" aria-label="Aucune image"><i class="bi bi-book" aria-hidden="true"></i></span><?php endif; ?></td>
            <td class="fw-semibold"><?= htmlspecialchars($livre['titre'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($livre['auteur'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($livre['categorie'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string) $livre['annee'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= (int) $livre['nb_exemplaires'] ?></td>
            <td><div class="d-flex justify-content-end gap-2">
              <a class="btn btn-outline-primary btn-sm" href="<?= APP_URL ?>/livres/modifier/<?= (int) $livre['id'] ?>"><i class="bi bi-pencil me-1"></i>Modifier</a>
              <form action="<?= APP_URL ?>/livres/supprimer/<?= (int) $livre['id'] ?>" method="POST" onsubmit="return confirm('Supprimer ce livre ?');">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i>Supprimer</button>
              </form>
            </div></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table></div></div>
  <?php endif; ?>
</div>
