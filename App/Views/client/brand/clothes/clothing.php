<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Costum-------->
<?= $this->asset('css/custom/client/clothing/clothing', 'css') ?? ''?>
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<main id="main-site">
   <!-- Content -->
   <?=$main_clothes_section ?? ''?>
   <!-- Brand section --------------->
   <?= $brand_section ?? ''?>

   <!-- Arrivals section --------------->
   <?= $arrival_section ?? ''?>

   <!-- Featured section --------------->
   <?= $features_section ?? ''?>

   <!-- MiddleSeason section --------------->
   <?= $middle_season ?? ''?>

   <!-- Dresses and suits section --------------->
   <?= $dresses_suits_section ?? ''?>

   <!-- Dresses and suits section --------------->
   <?= $best_wishes_section ?? ''?>

   <!-- Fin Content -->
   <input type="hidden" id="ipAddress" style="display:none" value="<?=H_visitors::getIP()?>">
</main>
<?php $this->end(); ?>
<?php $this->start('footer') ?>
<!----------custom--------->
<?= $this->asset('js/custom/client/clothing/clothing', 'js') ?? ''?>
<?php $this->end();
