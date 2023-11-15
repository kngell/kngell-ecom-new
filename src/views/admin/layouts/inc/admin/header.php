<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <meta name="description" content="E-commerce Dashboard">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
   <?=$this->asset('img/favicon', 'ico')?>
   <meta name="csrftoken" content="<?=$this->token->create(8, $this->getPageTitle())?>" />
   <meta name="frm_name" content="<?=$this->getPageTitle()?>" />
   <title><?=$this->getSiteTitle()?>
   </title>
   <!-- Librairies -->
   <?= $this->asset('css/librairies/adminlib', 'css') ?? ''?>
   <!-- Common modules css -->
   <?= $this->asset('commons/admin/commoncss', 'css') ?? ''?>
   <!-- Plugins css -->
   <?= $this->asset('css/plugins/adminplugins', 'css') ?? ''?>
   <!-- Main style -->
   <?= $this->asset('css/admin/main/main', 'css') ?? ''?>
   <!-- Custom css -->
   <?= $this->content('head'); ?>
</head>

<body>