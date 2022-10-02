<?php declare(strict_types=1);
require_once 'inc/adminTest/header.php'?>

<div class="grid-container">
   <!-- Header -->
   <?php require_once 'inc/adminTest/topbar.php'?>
   <!-- End Header -->

   <!-- Sidebar -->
   <?php require_once 'inc/adminTest/sidebar.php'?>
   <!-- End Sidebar -->

   <?= $this->content('body'); ?>

   <?php require_once 'inc/adminTest/footer.php'?>
</div>