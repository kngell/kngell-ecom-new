<?php declare(strict_types=1);
require_once 'inc/admin/header.php'?>

<div class="grid-container">
   <!-- Header -->
   <?php require_once 'inc/admin/topbar.php'?>
   <!-- End Header -->

   <!-- Sidebar -->
   <?php require_once 'inc/admin/sidebar.php'?>
   <!-- End Sidebar -->

   <?= $this->content('body'); ?>

   <?php require_once 'inc/admin/footer.php'?>
</div>