<?php
$currentLang = current_language();
$availableLangs = available_languages();
?>
<li class="nav-item dropdown">
   <a class="nav-link" data-toggle="dropdown" href="#">
      <i class="flag-icon flag-icon-<?= $availableLangs[$currentLang]['flag'] ?>"></i>
      <?= strtoupper($currentLang) ?>
   </a>
   <div class="dropdown-menu dropdown-menu-right p-0">
      <?php foreach ($availableLangs as $code => $lang): ?>
      <?php if ($code !== $currentLang): ?>
      <a href="<?= site_url("language/switch/{$code}") ?>" class="dropdown-item">
         <i class="flag-icon flag-icon-<?= $lang['flag'] ?> mr-2"></i>
         <?= $lang['name'] ?>
      </a>
      <?php endif; ?>
      <?php endforeach; ?>
   </div>
</li>