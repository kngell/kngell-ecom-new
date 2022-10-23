<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Costum-------->
<?= $this->asset('path', 'css') ?? ''?>
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<main id="main-site">
   <!-- Content -->
   <section class="py-5 products-features container" id="products-features">
      <div class="pb-5 features-title">
         <h2 class="title fw-bold">Our Features</h2>
         <hr class="horizontal-line">
         <p class="descr">Here you can check out our new products with fair price on Rymo</p>
      </div>
      <?=$shop_products ?? ''?>
   </section>
   <!-- Fin Content -->
   <input type="hidden" id="ip_address" style="display:none" value="<?=H_visitors::getIP()?>">
</main>
<?php $this->end(); ?>
<?php $this->start('footer') ?>
<!----------custom--------->
<?= $this->asset('path', 'js') ?? ''?>
<?php $this->end();