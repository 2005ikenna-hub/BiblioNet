<?php
$action = $isNew ? APP_URL . '/livres/creer/submit' : APP_URL . '/livres/modifier/' . (int) $livre['id'] . '/submit';
$escape = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<div class="container py-4">
  <div class="card bn-form-card shadow-sm"><div class="card-body p-4 p-md-5">
    <h1 class="h3 mb-4"><?= $isNew ? 'Ajouter un livre' : 'Modifier un livre' ?></h1>
    <?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= $escape($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form action="<?= $action ?>" method="POST">
      <input type="hidden" name="csrf_token" value="<?= $escape($csrfToken) ?>">
      <div class="mb-3"><label for="titre" class="form-label">Titre *</label><input class="form-control" id="titre" name="titre" maxlength="200" required value="<?= $escape($livre['titre'] ?? '') ?>"></div>
      <div class="mb-3"><label for="auteur" class="form-label">Auteur *</label><input class="form-control" id="auteur" name="auteur" maxlength="150" required value="<?= $escape($livre['auteur'] ?? '') ?>"></div>
      <div class="mb-3"><label for="categorie" class="form-label">Catégorie</label><input class="form-control" id="categorie" name="categorie" maxlength="100" value="<?= $escape($livre['categorie'] ?? '') ?>"></div>
      <div class="row"><div class="col-md-6 mb-3"><label for="annee" class="form-label">Année *</label><input type="number" class="form-control" id="annee" name="annee" min="1" max="2155" required value="<?= $escape($livre['annee'] ?? '') ?>"></div>
      <div class="col-md-6 mb-3"><label for="nb_exemplaires" class="form-label">Nombre d'exemplaires *</label><input type="number" class="form-control" id="nb_exemplaires" name="nb_exemplaires" min="1" max="255" required value="<?= $escape($livre['nb_exemplaires'] ?? '1') ?>"></div></div>
      <div class="d-flex gap-2 justify-content-end mt-3"><a href="<?= APP_URL ?>/livres" class="btn btn-outline-secondary">Annuler</a><button type="submit" class="btn btn-primary">Enregistrer</button></div>
    </form>
  </div></div>
</div>
