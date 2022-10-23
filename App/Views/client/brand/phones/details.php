<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Accueil-------->
<?= $this->asset('css/custom/client/brand/phones/product/product', 'css') ?? ''?>
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<!-- Start Main -->
<main id="main-site">
   <!-- Products -->
   <?= $singleProduct ?? ''?>
   <!-- end Products -->
   <!-- Top Sales -->
   <?= $topSales ?? '' ?>
   <!-- End top Sales -->
</main>
<!-- End  Main -->
<?php $this->end(); ?>
<?php $this->start('footer')?>
<!-- Html visitors -->
<?= $this->asset('js/custom/client/brand/phones/product/product', 'js') ?? ''?>
<?php $this->end();