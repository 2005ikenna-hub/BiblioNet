<?php
$livres ??= [];
$adherents ??= [];
$selection ??= [];
$errors ??= [];
$csrfToken ??= '';
$escape = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$livresDisponibles = array_filter($livres, static fn ($livre): bool => (int) $livre['disponibles'] > 0);
?>
<div class="container py-4">
  <div class="card bn-form-card shadow-sm"><div class="card-body p-4 p-md-5">
    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
      <div><h1 class="h3 mb-1">Enregistrer un emprunt</h1><p class="text-muted mb-0">Choisissez un exemplaire disponible et son adhérent.</p></div>
      <a href="<?= APP_URL ?>/emprunts" class="btn btn-outline-secondary btn-sm">Retour à la liste</a>
    </div>

    <?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= $escape($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <?php if (!$adherents): ?><div class="alert alert-warning">Ajoutez d’abord un adhérent avant d’enregistrer un emprunt.</div><?php endif; ?>
    <?php if (!$livresDisponibles): ?><div class="alert alert-warning">Aucun exemplaire n’est disponible pour le moment.</div><?php endif; ?>

    <form action="<?= APP_URL ?>/emprunts/creer/submit" method="POST">
      <input type="hidden" name="csrf_token" value="<?= $escape($csrfToken) ?>">
      <div class="mb-3">
        <label for="livre_id" class="form-label">Livre *</label>
        <select class="form-select" id="livre_id" name="livre_id" required>
          <option value="">Sélectionner un livre</option>
          <?php foreach ($livres as $livre): ?>
            <?php $available = (int) $livre['disponibles'] > 0; ?>
            <option value="<?= (int) $livre['id'] ?>" <?= (string) ($selection['livre_id'] ?? '') === (string) $livre['id'] ? 'selected' : '' ?> <?= $available ? '' : 'disabled' ?>>
              <?= $escape($livre['titre']) ?> — <?= $available ? (int) $livre['disponibles'] . ' disponible(s)' : 'indisponible' ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-4">
        <label for="adherent_id" class="form-label">Adhérent *</label>
        <select class="form-select" id="adherent_id" name="adherent_id" required>
          <option value="">Sélectionner un adhérent</option>
          <?php foreach ($adherents as $adherent): ?>
            <option value="<?= (int) $adherent['id'] ?>" <?= (string) ($selection['adherent_id'] ?? '') === (string) $adherent['id'] ? 'selected' : '' ?>>
              <?= $escape($adherent['prenom'] . ' ' . $adherent['nom']) ?><?= $adherent['classe'] !== '' ? ' — ' . $escape($adherent['classe']) : '' ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="d-flex gap-2 justify-content-end"><a href="<?= APP_URL ?>/emprunts" class="btn btn-outline-secondary">Annuler</a><button type="submit" class="btn btn-primary" <?= (!$adherents || !$livresDisponibles) ? 'disabled' : '' ?>>Enregistrer l’emprunt</button></div>
    </form>
  </div></div>
</div>