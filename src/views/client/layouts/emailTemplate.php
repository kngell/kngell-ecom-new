<?php declare(strict_types=1);
require_once 'inc/Email/header.php'; ?>
<!----------------Body----------------------->
<?= $this->content('body'); ?>
<!----------------xBody---------------------->
<?php require_once 'inc/Email/footer.php';
