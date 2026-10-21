<?php
$action = $isNew ? APP_URL . '/adherents/creer/submit' : APP_URL . '/adherents/modifier/' . (int) $adherent['id'] . '/submit';
$escape = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<div class="container py-4">
  <div class="card bn-form-card shadow-sm"><div class="card-body p-4 p-md-5">
    <h1 class="h3 mb-4"><?= $isNew ? 'Ajouter un adhérent' : 'Modifier un adhérent' ?></h1>
    <?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= $escape($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form action="<?= $action ?>" method="POST">
      <input type="hidden" name="csrf_token" value="<?= $escape($csrfToken) ?>">
      <div class="mb-3"><label for="nom" class="form-label">Nom *</label><input class="form-control" id="nom" name="nom" maxlength="100" required value="<?= $escape($adherent['nom'] ?? '') ?>"></div>
      <div class="mb-3"><label for="prenom" class="form-label">Prénom *</label><input class="form-control" id="prenom" name="prenom" maxlength="100" required value="<?= $escape($adherent['prenom'] ?? '') ?>"></div>
      <div class="mb-3"><label for="classe" class="form-label">Classe</label><input class="form-control" id="classe" name="classe" maxlength="50" value="<?= $escape($adherent['classe'] ?? '') ?>"></div>
      <div class="mb-3"><label for="telephone" class="form-label">Téléphone</label><input class="form-control" id="telephone" name="telephone" type="tel" maxlength="20" value="<?= $escape($adherent['telephone'] ?? '') ?>"></div>
      <div class="d-flex gap-2 justify-content-end mt-3"><a href="<?= APP_URL ?>/adherents" class="btn btn-outline-secondary">Annuler</a><button type="submit" class="btn btn-primary">Enregistrer</button></div>
    </form>
  </div></div>
</div>
