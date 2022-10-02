<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <meta name="description" content="E-commerce Dashboard">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
   <meta name="csrftoken" content="<?=$this->token->create(8, $this->getPageTitle())?>" />
   <title><?=$this->getSiteTitle()?>
   </title>
   <!-- Librairies -->
   <link href="<?= $this->asset('css/librairies/adminlib', 'css') ?? ''?>" rel="stylesheet" type="text/css">

   <!-- Common modules css -->
   <link href="<?= $this->asset('commons/admin/commoncss', 'css') ?? ''?>" rel="stylesheet" type="text/css">
   <!-- Plugins css -->
   <link href="<?= $this->asset('css/plugins/adminplugins', 'css') ?? ''?>" rel="stylesheet" type="text/css">
   <!-- Main style -->
   <link href="<?= $this->asset('css/admin/main/main', 'css') ?? ''?>" rel="stylesheet" type="text/css">
   <!-- Custom css -->
   <?= $this->content('head'); ?>

</head>

<body>