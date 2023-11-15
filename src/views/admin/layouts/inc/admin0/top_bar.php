<nav class="navbar topbar">
   <div class="top-left-menu">
      <div id="form-global-search" class="rounded">
         <form>
            <div class="input-group position-relative">
               <input type="text" class="form-control" placeholder="Search..." id="top-search">
               <button type="submit">
                  <i class="fa-solid fa-magnifying-glass text-highlight"></i>
               </button>
            </div>
         </form>
      </div>
   </div>
   <ul class="list-unstyled top-right-menu">
      <li class="nav-item text-white-50 me-5"><a href="<?=PROOT . 'home'?>">
            <span><i class="fa-solid fa-house"></i></span>&nbsp;<span>Home</span></a>
      </li>
      <li class="nav-item topbar-dropdown">
         <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
            aria-expanded="false" id="emailsDropdown">
            <i class="fa-regular fa-envelope text-white"></i>
            <span class="badge bg-success rounded-circle">6</span>
         </a>
         <div class="dropdown-menu dropdown-animate dropdown-menu-lg dropdown-menu-right py-0"
            aria-labelledby="emailsDropdown">
            <div class="dropdown-menu-header">
               6 New Emails
            </div>
            <div class="list-group">
               <a href="#" class="list-group-item">
                  <div class="row no-gutters align-items-center">
                     <div class="col-2">
                        <img src="<?=IMG . 'users' . US . 'avatar.png'?>" class="user-avatar rounded-circle" alt="User">
                     </div>
                     <div class="col-10">
                        <div>Sam Robert</div>
                        <div class="text-muted small mt-1">Ability to select/create Modules</div>
                        <div class="text-muted small mt-1">1h ago</div>
                     </div>
                  </div>
               </a>
               <a href="#" class="list-group-item">
                  <div class="row no-gutters align-items-center">
                     <div class="col-2">
                        <img src="<?=IMG . 'users' . US . 'default-female-avatar.jpg'?>" alt="User"
                           class="user-avatar rounded-circle">
                     </div>
                     <div class="col-10">
                        <div>Schirsten Vander</div>
                        <div class="text-muted small mt-1">Add widgets descriptions</div>
                        <div class="text-muted small mt-1">2h ago</div>
                     </div>
                  </div>
               </a>
               <a href="#" class="list-group-item">
                  <div class="row no-gutters align-items-center">
                     <div class="col-2">
                        <img src="<?=IMG . 'users' . US . 'default-female-avatar.jpg'?>" alt="User"
                           class="user-avatar rounded-circle">
                     </div>
                     <div class="col-10">
                        <div>Robert Nordic</div>
                        <div class="text-muted small mt-1">Mark cancelled the event</div>
                        <div class="text-muted small mt-1">4h ago</div>
                     </div>
                  </div>
               </a>
               <a href="#" class="list-group-item">
                  <div class="row no-gutters align-items-center">
                     <div class="col-2">
                        <img src="<?=IMG . 'users' . US . 'default-female-avatar.jpg'?>" alt="User"
                           class="user-avatar rounded-circle">
                     </div>
                     <div class="col-10">
                        <div>Michael Branson</div>
                        <div class="text-muted small mt-1">Lorem ipsum dolor sit amet</div>
                        <div class="text-muted small mt-1">6h ago</div>
                     </div>
                  </div>
               </a>
               <a href="#" class="list-group-item">
                  <div class="row no-gutters align-items-center">
                     <div class="col-2">
                        <img src="<?=IMG . 'users' . US . 'default-female-avatar.jpg'?>" alt="User"
                           class="user-avatar rounded-circle">
                     </div>
                     <div class="col-10">
                        <div>Antonym Sladers</div>
                        <div class="text-muted small mt-1">Lorem ipsum dolor sit amet</div>
                        <div class="text-muted small mt-1">6h ago</div>
                     </div>
                  </div>
               </a>
               <a href="#" class="list-group-item">
                  <div class="row no-gutters align-items-center">
                     <div class="col-2">
                        <img src="<?=IMG . 'users' . US . 'default-female-avatar.jpg'?>" alt="User"
                           class="user-avatar rounded-circle">
                     </div>
                     <div class="col-10">
                        <div>Marcus</div>
                        <div class="text-muted small mt-1">Lorem ipsum dolor sit amet</div>
                        <div class="text-muted small mt-1">6h ago</div>
                     </div>
                  </div>
               </a>
            </div>
            <div class="dropdown-menu-footer">
               <a href="#" class="text-primary">View all</a>
            </div>
         </div>
      </li>
      <li class="nav-item task-list topbar-dropdown">
         <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
            aria-expanded="false" id="tasksDropdown">
            <i class="fa-solid fa-list-check text-white"></i>
            <span class="badge bg-danger rounded-circle">5</span>
         </a>
         <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right py-0 dropdown-animate"
            aria-labelledby="tasksDropdown">
            <div class="dropdown-menu-header">
               4 New Tasks
            </div>
            <div class="list-group">
               <a class="list-group-item" href="#">
                  <span class="task small">
                     <span class="desc">Design database</span>
                     <span class="percent">74%</span>
                  </span>
                  <div class="progress progress-sm m-0">
                     <div class="progress-bar bg-success" role="progressbar" aria-valuenow="74" aria-valuemin="0"
                        aria-valuemax="100" style="width: 74%">
                        <span class="sr-only">74% Complete</span>
                     </div>
                  </div>
               </a>
               <a class="list-group-item" href="#">
                  <span class="task small">
                     <span class="desc">Testing</span>
                     <span class="percent">30%</span>
                  </span>
                  <div class="progress progress-sm m-0">
                     <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="30" aria-valuemin="0"
                        aria-valuemax="100" style="width: 30%">
                        <span class="sr-only">30% Complete</span>
                     </div>
                  </div>
               </a>
               <a class="list-group-item" href="#">
                  <span class="task small">
                     <span class="desc">Document writting</span>
                     <span class="percent">50%</span>
                  </span>
                  <div class="progress progress-sm m-0">
                     <div class="progress-bar bg-info" role="progressbar" aria-valuenow="50" aria-valuemin="0"
                        aria-valuemax="100" style="width: 50%">
                        <span class="sr-only">50% Complete</span>
                     </div>
                  </div>
               </a>
               <a class="list-group-item" href="#">
                  <span class="task small">
                     <span class="desc">Development</span>
                     <span class="percent">45%</span>
                  </span>
                  <div class="progress progress-sm m-0">
                     <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="45" aria-valuemin="0"
                        aria-valuemax="100" style="width: 45%">
                        <span class="sr-only">45% Complete</span>
                     </div>
                  </div>
               </a>
               <a class="list-group-item" href="#">
                  <span class="task small">
                     <span class="desc">HTML slicing</span>
                     <span class="percent">66%</span>
                  </span>
                  <div class="progress progress-sm m-0">
                     <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="66" aria-valuemin="0"
                        aria-valuemax="100" style="width: 66%">
                        <span class="sr-only">66% Complete</span>
                     </div>
                  </div>
               </a>
            </div>
            <div class="dropdown-menu-footer">
               <a href="#" class="text-primary">View all</a>
            </div>
         </div>
      </li>
      <li class="nav-item notification-list topbar-dropdown">
         <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
            aria-expanded="false" id="notificationsDropdown">
            <i class="fa-regular fa-bell text-white"></i>
            <span class="badge bg-info rounded-circle">4</span>
         </a>
         <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right py-0 dropdown-animate"
            aria-labelledby="notificationsDropdown">
            <div class="dropdown-menu-header">
               4 New Notifications
            </div>
            <div class="list-group">
               <a href="#" class="list-group-item">
                  <div class="row align-items-center">
                     <div class="col-2">
                        <i class="ms-1 text-primary fas fa-fw fa-user"></i>
                     </div>
                     <div class="col-10">
                        <div>New user registered</div>
                        <div class="text-muted small mt-1">Lorem ipsum dolor sit amet</div>
                        <div class="text-muted small mt-1">1h ago</div>
                     </div>
                  </div>
               </a>
               <a href="#" class="list-group-item">
                  <div class="row align-items-center">
                     <div class="col-2">
                        <i class="ms-1 text-warning fas fa-fw fa-bell"></i>
                     </div>
                     <div class="col-10">
                        <div>Update completed</div>
                        <div class="text-muted small mt-1">Lorem ipsum dolor sit amet.</div>
                        <div class="text-muted small mt-1">2h ago</div>
                     </div>
                  </div>
               </a>
               <a href="#" class="list-group-item">
                  <div class="row align-items-center">
                     <div class="col-2">
                        <i class="ms-1 text-info fas fa-fw fa-envelope-open"></i>
                     </div>
                     <div class="col-10">
                        <div>Mark cancelled the event</div>
                        <div class="text-muted small mt-1">Lorem ipsum dolor sit amet, consectetur adip</div>
                        <div class="text-muted small mt-1">4h ago</div>
                     </div>
                  </div>
               </a>
               <a href="#" class="list-group-item">
                  <div class="row align-items-center">
                     <div class="col-2">
                        <i class="ms-1 text-danger fas fa-fw fa-building"></i>
                     </div>
                     <div class="col-10">
                        <div>Login from 192.168.1.1</div>
                        <div class="text-muted small mt-1">6h ago</div>
                     </div>
                  </div>
               </a>
            </div>
            <div class="dropdown-menu-footer">
               <a href="#" class="text-primary">View all</a>
            </div>
         </div>
      </li>
      <li class="nav-item user-setting-list topbar-dropdown">
         <a class="nav-link dropdown-toggle nav-profile text-white-50" data-bs-toggle="dropdown" href="#" role="button"
            aria-haspopup="false" aria-expanded="false">
            <img src="<?=IMG . 'users' . US . 'avatar.png'?>" alt="user-image" class="rounded-circle img-fluid">
            <span class="ms-1 d-none d-lg-inline">
               <span class="user-name">Bonjour,Daniel</span>
            </span>
         </a>
         <div class="dropdown-menu dropdown-menu-right profile-dropdown dropdown-animate">
            <div class="dropdown-header">
               <h6 class="text-center">Bienvenu !</h6>
            </div>
            <a href="<?=PROOT . 'account' . US . 'account'?>" class="dropdown-item">
               <i class="fa-solid fa-user-large me-1"></i>
               <span>Mon compte</span>
            </a>
            <a href="javascript:void(0);" class="dropdown-item">
               <i class="fa-solid fa-gear me-1"></i>
               <span>Settings</span>
            </a>
            <a href="javascript:void(0);" class="dropdown-item">
               <i class="fa-regular fa-envelope me-1"></i>
               <span>Inbox</span>
            </a>
            <a href="javascript:void(0);" class="dropdown-item">
               <i class="fa-solid fa-calendar-days me-1"></i>
               <span>Calendar</span>
            </a>
            <a href="javascript:void(0);" class="dropdown-item">
               <i class="fa-solid fa-list-check me-1"></i>
               <span>Tasks</span>
            </a>
            <a href="javascript:void(0);" class="dropdown-item">
               <i class="fa-solid fa-right-from-bracket me-1"></i>
               <span>Logout</span>
            </a>
         </div>
      </li>
      <li class="nav-item">
         <a href="#right-sidebar" class="nav-link" data-bs-toggle="modal" id="modalSetting">
            <i class="fal fa-cog rotate text-white-50"></i>
         </a>
      </li>
   </ul>
</nav>