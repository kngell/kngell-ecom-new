<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Costum-------->
<?= $this->asset('path', 'css') ?? ''?>
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<main id="main-site">
   <!-- Content -->

   <!-- product details --------------->
   <?= $details ?? ''?>

   <!-- product details --------------->
   <?= $related_products ?? ''?>

   <!-- Fin Content -->

</main>
<?php $this->end(); ?>
<?php $this->start('footer') ?>
<!----------custom--------->
<?= $this->asset('path', 'js') ?? ''?>
<?php $this->end();