<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Costum-------->
<link href="<?= $this->asset('css/admin/map/map', 'css') ?? ''?>" rel="stylesheet" type="text/css">
<?php $this->end(); ?>
<?php $this->start('body'); ?>

<main id="main" class="main-container">
   <div class="main-head">
      <h1 class="main-head__title mb-4"><span class="text-highlight">E-Commerce</span> <span
            class="fw-300">Dashboard</span>
      </h1>
   </div>
   <div class="main-body">
      <div class="row main-cards">
         <div class="col-md-6 col-xl-3">
            <div class="card">
               <div class="card-body">
                  <div class="row">
                     <div class="col-6">
                        <p class="text-muted mb-1 text-truncate">Total Earnings
                        </p>
                     </div>
                     <div class="col-6">
                        <div class="icon-sm bg-primary rounded float-end">
                           <span class="material-icons-outlined">
                              euro
                           </span>
                        </div>
                     </div>
                  </div>
                  <h2 class="my-1 fw-300">$24,431</h2>
                  <div class="mt-3">
                     <h6>Target <span class="float-end">59%</span></h6>
                     <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="59" aria-valuemin="0"
                           aria-valuemax="100" style="width: 59%">
                           <span class="sr-only">59% Complete</span>
                        </div>
                     </div>
                  </div>
               </div>
            </div> <!-- end card-->
         </div> <!-- end col -->
         <div class="col-md-6 col-xl-3">
            <div class="card">
               <div class="card-body">
                  <div class="row">
                     <div class="col-6">
                        <p class="text-muted mb-1 text-truncate">Sales Today</p>
                     </div>
                     <div class="col-6">
                        <div class="icon-sm bg-success rounded float-end">
                           <span class="material-icons-outlined">
                              shopping_cart
                           </span>
                        </div>
                     </div>
                  </div>
                  <h2 class="my-1 fw-300">1,576</h2>
                  <div class="mt-3">
                     <h6>Target <span class="float-end">68%</span></h6>
                     <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="68" aria-valuemin="0"
                           aria-valuemax="100" style="width: 68%">
                           <span class="sr-only">68% Complete</span>
                        </div>
                     </div>
                  </div>
               </div>
            </div> <!-- end card-->
         </div> <!-- end col -->
         <div class="col-md-6 col-xl-3">
            <div class="card">
               <div class="card-body">
                  <div class="row">
                     <div class="col-6">
                        <p class="text-muted mb-1 text-truncate">Pending Orders</p>
                     </div>
                     <div class="col-6">
                        <div class="icon-sm bg-info rounded float-end">
                           <i class="fa-solid fa-user-clock"></i>
                        </div>
                     </div>
                  </div>
                  <h2 class="my-1 fw-300"><span>234</span></h2>
                  <div class="mt-3">
                     <h6>Target <span class="float-end">74%</span></h6>
                     <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-info" role="progressbar" aria-valuenow="74" aria-valuemin="0"
                           aria-valuemax="100" style="width: 74%">
                           <span class="sr-only">74% Complete</span>
                        </div>
                     </div>
                  </div>
               </div>
            </div> <!-- end card-->
         </div> <!-- end col -->
         <div class="col-md-6 col-xl-3">
            <div class="card">
               <div class="card-body">
                  <div class="row">
                     <div class="col-6">
                        <p class="text-muted mb-1 text-truncate">Payouts</p>
                     </div>
                     <div class="col-6">
                        <div class="icon-sm bg-warning rounded float-end">
                           <span class="material-icons-outlined">
                              receipt
                           </span>
                        </div>
                     </div>
                  </div>
                  <h2 class="my-1 fw-300">$4,321</h2>
                  <div class="mt-3">
                     <h6>Target <span class="float-end">76%</span></h6>
                     <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="76" aria-valuemin="0"
                           aria-valuemax="100" style="width: 76%">
                           <span class="sr-only">76% Complete</span>
                        </div>
                     </div>
                  </div>
               </div>
            </div> <!-- end card-->
         </div> <!-- end col -->
      </div>
      <div class="row maps">
         <div class="col-md-6 d-flex">
            <div class="card w-100 ">
               <div class="d-flex justify-content-between p-3">
                  <h5 class="card-title mb-0">Location</h5>
                  <div class="card-actions">
                     <div class="d-inline-block dropdown show">
                        <a href="#" data-bs-toggle="dropdown" data-display="static">
                           <i class="fa-solid fa-ellipsis"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                           <a class="dropdown-item" href="#">Action</a>
                           <a class="dropdown-item" href="#">Export</a>
                           <a class="dropdown-item" href="#">Profit</a>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="card-body px-4">
                  <div id="world_map"></div>
               </div>
            </div>
         </div>
         <div class="col-md-6 d-flex">
            <div class="card flex-fill w-100 ">
               <div class="d-flex justify-content-between p-3">
                  <h5 class="card-title mb-0">Total Sales</h5>
                  <div class="card-actions float-end">
                     <div class="d-inline-block dropdown show">
                        <a href="#" data-bs-toggle="dropdown" data-display="static">
                           <i class="far fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                           <a class="dropdown-item" href="#">Action</a>
                           <a class="dropdown-item" href="#">Export</a>
                           <a class="dropdown-item" href="#">Profit</a>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="card-body px-4">
                  <div class="">
                     <canvas id="dashboard-doughnut-chartjs"></canvas>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="charts-sales">
         <div class="charts-card">
            <p class="chart-title">Top 5 Products</p>
            <div id="bar-chart"></div>
         </div>
         <div class="charts-card">
            <p class="chart-title">Purchase and Sales Orders</p>
            <div id="area-chart"></div>
         </div>
      </div>

   </div>
</main>
<?php $this->end(); ?>

<?php $this->start('footer') ?>
<!----------custom--------->
<script type="text/javascript" src="<?= $this->asset('js/admin/map/map', 'js') ?? ''?>">
</script>
<?php $this->end();