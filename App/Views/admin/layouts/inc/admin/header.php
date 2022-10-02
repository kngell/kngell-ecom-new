<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <meta name="description" content="E-commerce Dashboard">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
   <meta property="og:image" content="https://www.teachprod.com/wp-content/uploads/2020/10/carrot.jpg">
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

   <script async src="https://www.googletagmanager.com/gtag/js?id=UA-180917586-1"></script>
   <script>
   window.dataLayer = window.dataLayer || [];

   function gtag() {
      dataLayer.push(arguments);
   }
   gtag('js', new Date());

   gtag('config', 'UA-180917586-1');
   </script>
</head>
<!-- Possible Body Attributes
    * data-theme-header-fixed = 'true'       - For fixed Header
    * data-theme-header-fixed = 'false'      - For static Header
    * data-theme-sidebar-fixed = 'true'      - For fixed Left Navigation
    * data-theme-sidebar-fixed = 'false'     - For static Left Navigation
    * data-theme-sidebar-shrinked = 'true'   - For shrinking Left Navigation
    * data-theme-footer-fixed = 'true'       - For fixed Footer
    * data-theme-footer-fixed = 'false'      - For static Footer
    * data-theme-mode = 'dark-mode'          - For Dark Mode
-->

<body>
   <!-- apply javascript before page content be loaded -->
   <script>
   'use strict';
   var defaultConfig = {
      fixedLeftSidebar: true,
      fixedHeader: false,
      fixedFooter: false,
      isShrinked: false,
      themeColor: 'app-theme-facebook',
      themeMode: 'default-mode'
   };
   var globalConfigs = JSON.parse(localStorage.getItem('ABCADMIN_CONFIG')) || defaultConfig;
   var appThemeMode = globalConfigs.themeMode;
   var isShrinked = globalConfigs.isShrinked;
   var body = document.getElementsByTagName("body")[0];
   body.setAttribute("data-theme-mode", appThemeMode);
   body.setAttribute("data-theme-sidebar-shrinked", isShrinked)
   </script>