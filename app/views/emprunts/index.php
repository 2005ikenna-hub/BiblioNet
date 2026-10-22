<?php
$emprunts ??= [];
$csrfToken ??= '';
?>
<div class="container py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <h1 class="h2 mb-1">Gestion des emprunts</h1>
      <p class="text-muted mb-0"><?= count($emprunts) ?> emprunt(s) dans l’historique</p>
    </div>
    <a href="<?= APP_URL ?>/emprunts/creer" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Enregistrer un emprunt</a>
  </div>

  <?php if (!$emprunts): ?>
    <div class="alert alert-info">Aucun emprunt enregistré pour le moment.</div>
  <?php else: ?>
    <div class="card shadow-sm"><div class="table-responsive"><table class="table table-hover align-middle mb-0 bn-table">
      <thead><tr><th>Livre</th><th>Adhérent</th><th>Date d’emprunt</th><th>État</th><th class="text-end">Action</th></tr></thead>
      <tbody>
        <?php foreach ($emprunts as $emprunt): ?>
          <tr>
            <td class="fw-semibold"><?= htmlspecialchars($emprunt['livre_titre'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($emprunt['adherent_prenom'] . ' ' . $emprunt['adherent_nom'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($emprunt['date_emprunt'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
            <td>
              <?php if ($emprunt['date_retour'] === null): ?>
                <span class="badge text-bg-warning">En cours</span>
                <?php if ((int) $emprunt['jours_ecoules'] > 14): ?><span class="badge text-bg-danger">En retard</span><?php endif; ?>
              <?php else: ?>
                <span class="badge text-bg-success">Rendu le <?= htmlspecialchars($emprunt['date_retour'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
              <?php endif; ?>
            </td>
            <td class="text-end">
              <?php if ($emprunt['date_retour'] === null): ?>
                <form action="<?= APP_URL ?>/emprunts/retour/<?= (int) $emprunt['id'] ?>" method="POST" class="m-0">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                  <button type="submit" class="btn btn-outline-success btn-sm btn-action" aria-label="Enregistrer le retour" title="Enregistrer le retour"><i class="bi bi-check2" aria-hidden="true"></i></button>
                </form>
              <?php else: ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table></div></div>
  <?php endif; ?>
</div>
