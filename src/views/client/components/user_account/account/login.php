<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Costum-------->
<link href="<?= $this->asset('css/custom/client/users/account/login', 'css') ?? ''?>" rel="stylesheet" type="text/css">
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<main id="main-site">
    <!-- Content -->
    <?= $this->log_file?>
    <?php require_once VIEW . 'client/home/partials/_new_products.php'?>

    <!-- Fin Content -->
    <input type="hidden" id="ipAddress" style="display:none" value="<?=H_visitors::getIP()?>">
</main>
<?php $this->end(); ?>
<?php $this->start('footer') ?>
<!----------custom--------->
<script type="text/javascript" src="<?= $this->asset('js/custom/client/users/account/login', 'js') ?? ''?>">
</script>
<?php $this->end();
