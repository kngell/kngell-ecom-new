<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Costum-------->
<link href="<?= $this->asset('css/learn/learn', 'css') ?? ''?>" rel="stylesheet" type="text/css">
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<main id="main-site">
   <!-- Content -->
   <div class="container k-pt-2">
      <nav class="header">
         <div class="left-section">
            <img src="../../../assets/img/learn/icons/hamburger-menu.svg" class="hamburger-menu" alt="">
            <img src="../../../assets/img/learn/icons/youtube-logo.svg" class="youtube-logo" alt="">
         </div>
         <div class="middle-section">
            <input type="text" class="search-bar" placeholder="Search">
            <button class="search-button">
               <img src="../../../assets/img/learn/icons/search.svg" class="search-icon" alt="">
               <div class="tooltip">Search</div>
            </button>
            <button class="voice-search-button">
               <img src="../../../assets/img/learn/icons/voice-search-icon.svg" class="voice-search-icon" alt="">
               <div class="tooltip">Search with your voice</div>
            </button>
         </div>
         <div class="right-section">
            <div class="upload-icon-container">
               <img src="../../../assets/img/learn/icons/upload.svg" class="upload-icon" alt="">
               <div class="tooltip">upload</div>
            </div>
            <img src="../../../assets/img/learn/icons/youtube-apps.svg" class="youtube-apps-icon" alt="">
            <div class="notification-icon-container">
               <img src="../../../assets/img/learn/icons/notifications.svg" class="notification-icon" alt="">
               <div class="notification-count">3</div>
            </div>
            <img src="../../../assets/img/learn/my-channel.jpeg" class="current-user-picture" alt="">
         </div>

      </nav>
      <nav class="sidebar">
         <div class="sidebar-link">
            <img src="../../../assets/img/learn/icons/home.svg" alt="">
            <div>Home</div>
         </div>
         <div class="sidebar-link">
            <img src="../../../assets/img/learn/icons/explore.svg" alt="">
            <div>Explore</div>
         </div>
         <div class="sidebar-link">
            <img src="../../../assets/img/learn/icons/subscriptions.svg" alt="">
            <div>Suscription</div>
         </div>
         <div class="sidebar-link">
            <img src="../../../assets/img/learn/icons/originals.svg" alt="">
            <div>Originals</div>
         </div>
         <div class="sidebar-link">
            <img src="../../../assets/img/learn/icons/youtube-music.svg" alt="">
            <div>Youtune Music</div>
         </div>
         <div class="sidebar-link">
            <img src="../../../assets/img/learn/icons/library.svg" alt="">
            <div>Librairy</div>
         </div>
      </nav>
      <section class="video-grid">
         <div class="video-preview">
            <div class="thumbnail-row">
               <img src="../../../assets/img/learn/thumbnail-1.webp" class="thumbnail" alt="">
               <div class="video-time">
                  14:20
               </div>
            </div>
            <div class="video-info-grid">
               <div class="channel-picture"> <img src="../../../assets/img/learn/channel-1.jpeg" class="profile-picture"
                     alt=""></div>
               <div class="video-info">
                  <p class="video-title">Talking Tech and AI with Google CEO Sundar Pichai!</p>
                  <p class="video-author">Marques Brownlee</p>
                  <p class="video-stat">3.4M views &#183; 6 months ago</p>
               </div>

            </div>
         </div>
         <div class="video-preview">
            <div class="thumbnail-row"><img src="../../../assets/img/learn/thumbnail-2.webp" class="thumbnail" alt="">
               <div class="video-time">
                  12:40
               </div>
            </div>
            <div class="video-info-grid">
               <div class="channel-picture"> <img src="../../../assets/img/learn/channel-2.jpeg" class="profile-picture"
                     alt=""></div>
               <div class="video-info">
                  <p class="video-title">Try Not To Laugh Challenge #9</p>
                  <p class="video-author">Markiplier</p>
                  <p class="video-stat">19M views &#183; 4 years ago</p>
               </div>

            </div>
         </div>
         <div class="video-preview">
            <div class="thumbnail-row"><img src="../../../assets/img/learn/thumbnail-3.webp" class="thumbnail" alt="">
               <div class="video-time">
                  22:30
               </div>
            </div>
            <div class="video-info-grid">
               <div class="channel-picture"> <img src="../../../assets/img/learn/channel-3.jpeg" class="profile-picture"
                     alt=""></div>
               <div class="video-info">
                  <p class="video-title">Crazy Tik Toks Taken Moments Before DISASTER</p>
                  <p class="video-author">SSSniperWolf</p>
                  <p class="video-stat">12M views &#183; 1 year ago</p>
               </div>

            </div>
         </div>
         <div class="video-preview">
            <div class="thumbnail-row"><img src="../../../assets/img/learn/thumbnail-4.webp" class="thumbnail" alt="">
               <div class="video-time">
                  08:25
               </div>
            </div>
            <div class="video-info-grid">
               <div class="channel-picture"> <img src="../../../assets/img/learn/channel-4.jpeg" class="profile-picture"
                     alt=""></div>
               <div class="video-info">
                  <p class="video-title">The Simplest Math Problem No One Can Solve - Collatz Conjecture</p>
                  <p class="video-author">Veritasium</p>
                  <p class="video-stat">18M views &#183; 4 months ago</p>
               </div>

            </div>
         </div>
         <div class="video-preview">
            <div class="thumbnail-row"><img src="../../../assets/img/learn/thumbnail-5.webp" class="thumbnail" alt="">
               <div class="video-time">
                  09:20
               </div>
            </div>
            <div class="video-info-grid">
               <div class="channel-picture"> <img src="../../../assets/img/learn/channel-5.jpeg" class="profile-picture"
                     alt=""></div>
               <div class="video-info">
                  <p class="video-title">Kadane's Algorithm to Maximum Sum Subarray Problem</p>
                  <p class="video-author">CS Dojo</p>
                  <p class="video-stat">519K views &#183; 5 years ago</p>
               </div>

            </div>
         </div>
         <div class="video-preview">
            <div class="thumbnail-row"><img src="../../../assets/img/learn/thumbnail-6.webp" class="thumbnail" alt="">
               <div class="video-time">
                  10:00
               </div>
            </div>
            <div class="video-info-grid">
               <div class="channel-picture"> <img src="../../../assets/img/learn/channel-6.jpeg" class="profile-picture"
                     alt=""></div>
               <div class="video-info">
                  <p class="video-title">Anything You Can Fit In The Circle I’ll Pay For</p>
                  <p class="video-author">MrBeast</p>
                  <p class="video-stat">141M views &#183; 1 year ago</p>
               </div>

            </div>
         </div>
         <div class="video-preview">
            <div class="thumbnail-row"><img src="../../../assets/img/learn/thumbnail-1.webp" class="thumbnail" alt="">
               <div class="video-time">
                  14:30
               </div>
            </div>
            <div class="video-info-grid">
               <div class="channel-picture"> <img src="../../../assets/img/learn/channel-1.jpeg" class="profile-picture"
                     alt=""></div>
               <div class="video-info">
                  <p class="video-title">Talking Tech and AI with Google CEO Sundar Pichai!</p>
                  <p class="video-author">Marques Brownlee</p>
                  <p class="video-stat">3.4M views &#183; 6 months ago</p>
               </div>

            </div>
         </div>
         <div class="video-preview">
            <div class="thumbnail-row"><img src="../../../assets/img/learn/thumbnail-2.webp" class="thumbnail" alt="">
               <div class="video-time">
                  07:06
               </div>
            </div>
            <div class="video-info-grid">
               <div class="channel-picture"> <img src="../../../assets/img/learn/channel-2.jpeg" class="profile-picture"
                     alt=""></div>
               <div class="video-info">
                  <p class="video-title">Try Not To Laugh Challenge #9</p>
                  <p class="video-author">Markiplier</p>
                  <p class="video-stat">19M views &#183; 4 years ago</p>
               </div>

            </div>
         </div>
      </section>
   </div>
   <!-- Fin Content -->
   <input type="hidden" id="ip_address" style="display:none" value="<?=H_visitors::getIP()?>">
</main>
<?php $this->end(); ?>
<?php $this->start('footer') ?>
<!----------custom--------->
<script type="text/javascript" src="<?= $this->asset('pathjs', 'js') ?? ''?>">
</script>
<?php $this->end();
