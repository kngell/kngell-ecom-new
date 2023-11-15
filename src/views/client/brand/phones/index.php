<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Costum-------->
<?= $this->asset('css/client/brand/phones/home/home', 'css') ?? ''?>
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<!-- Start Main -->
<main id="main-site">
   <div class="page-content">
      <!-- Owl carousel -->
      <?= $bannerArea ?? ''?>
      <!-- End Owl carousel -->

      <!-- Top Sales -->
      <?= $topSales ?? ''?>
      <!-- End top Sales -->

      <!-- Special price -->
      <?= $specialPrice ?? ''?>
      <!-- End Special price -->

      <!-- Banner Adds -->
      <?= $bannerAdds ?? ''?>
      <!-- End Banner Adds -->

      <!-- New Phones -->
      <?= $newProducts ?? ''?>
      <!-- End New Phones -->

      <!-- Blog -->
      <?= $blogArea ?? ''?>
      <!-- End blog -->
   </div>
   <input type="hidden" id="ipAddress" style="display:none" value="<?=H_visitors::getIP()?>">
</main>
<!-- End  Main -->
<?php $this->end(); ?>
<?php $this->start('footer') ?>
<!----------custom--------->
<?= $this->asset('js/client/brand/phones/home/home', 'js') ?? ''?>
<?php $this->end();