<div class="container py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div><h1 class="h2 mb-1">Gestion des adhérents</h1><p class="text-muted mb-0"><?= count($adherents) ?> adhérent(s) enregistré(s)</p></div>
    <a href="<?= APP_URL ?>/adherents/creer" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Ajouter un adhérent</a>
  </div>
  <?php if (!$adherents): ?>
    <div class="alert alert-info">Aucun adhérent enregistré. Ajoutez le premier adhérent pour commencer.</div>
  <?php else: ?>
    <div class="card shadow-sm"><div class="table-responsive"><table class="table table-hover align-middle mb-0 bn-table">
      <thead><tr><th>Photo</th><th>Nom</th><th>Prénom</th><th>Classe</th><th>Téléphone</th><th class="text-end">Actions</th></tr></thead>
      <tbody>
        <?php foreach ($adherents as $adherent): ?>
          <tr>
            <td><?php if (!empty($adherent['image_path'])): ?><img src="<?= APP_URL ?>/<?= htmlspecialchars($adherent['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="Photo de <?= htmlspecialchars($adherent['prenom'] . ' ' . $adherent['nom'], ENT_QUOTES, 'UTF-8') ?>" class="bn-table-thumbnail bn-table-avatar" data-fallback-class="bn-table-placeholder bn-table-avatar" data-fallback-icon="bi bi-person"><?php else: ?><span class="bn-table-placeholder bn-table-avatar" aria-label="Aucune photo"><i class="bi bi-person" aria-hidden="true"></i></span><?php endif; ?></td>
            <td class="fw-semibold"><?= htmlspecialchars($adherent['nom'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($adherent['prenom'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($adherent['classe'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($adherent['telephone'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><div class="d-flex justify-content-end gap-2">
              <a class="btn btn-outline-primary btn-sm" href="<?= APP_URL ?>/adherents/modifier/<?= (int) $adherent['id'] ?>"><i class="bi bi-pencil me-1"></i>Modifier</a>
              <form action="<?= APP_URL ?>/adherents/supprimer/<?= (int) $adherent['id'] ?>" method="POST" onsubmit="return confirm('Supprimer cet adhérent ?');">
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
