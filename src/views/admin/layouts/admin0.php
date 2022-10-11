<?php declare(strict_types=1);
require_once 'inc/admin/header.php'?>
<?php require_once 'inc/admin/side_bar.php'; ?>
<div class="page-container">
   <?php require_once 'inc/admin/top_bar.php'?>
   <?= $this->content('body'); ?>
   <?php require_once 'inc/admin/modal.php'?>
   <?php require_once 'inc/admin/footer.php'?>
</div>