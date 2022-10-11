<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Costum-------->
<?= $this->asset('css/custom/backend/admin/products/brands', 'css') ?? ''?>
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<main id="main" class="main-container">
   <!-- Content Header (Page header) -->
   <div class="row content-header justify-content-between mb-4 w-100">
      <div class="col">
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb ps-0 fs-base">
               <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
               <li class="breadcrumb-item"><span>Products</span></li>
               <li class="breadcrumb-item active" aria-current="page">All Brands</li>
            </ol>
         </nav>
      </div>
      <div class="col text-end">
         <h4 class="header-title h3">
            Manage Brands
         </h4>
      </div>
   </div>
   <!-- /.content-header -->
   <!-- Main content -->
   <div class="row mb-4 content">
      <div class="col-12">
         <!-- Small boxes (Stat box) -->
         <div class="card border-primary" id="brand-wrapper">
            <h5 class="card-header d-flex">
               <span class="text-light lead">Manage Brands</span>
               <span class="ms-auto"> <a href="javascript:history.go(-1)" class="btn btn-light btn-secondary"
                     id="back"><i class="far fa-arrow-alt-circle-left fa-lg"></i></i>&nbsp;Back
                  </a>&nbsp;&nbsp;
                  <a href="" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-box" id="addNew"><i
                        class="fas fa-plus-circle fa-lg"></i>&nbsp;Add new</a>
               </span>
            </h5>
            <div id="globalErr"></div>
            <div class="card-body">
               <div class="container">
                  <div class="row">
                     <div class="table-responsive col-lg-12" id="showAll">
                        <p class="text-center lead mt-5">Please wait...</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!----------Add new categorie Modal-------->
   <div class="modal fade" role="dialog" id="modal-box">
      <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title"> Brands</h5>
               <button type="button" class="btn-close text-light" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
               <form action="#" method="post" id="brands-frm" class="px-3 needs-validation" novalidate
                  enctype="multipart/form-data">
                  {{token}}
                  <input type="hidden" name="operation" id="operation">
                  <input type="hidden" name="brID" id="brID">
                  <input type="hidden" name="created_at" id="created_at">
                  <input type="hidden" name="updated_at" id="updated_at">
                  <input type="hidden" name="deleted" id="deleted">
                  <div id="alertErr"></div>
                  <div class="mb-3">
                     <input type="text" name="br_name" id="br_name" class="form-control " placeholder="Brand Name"
                        aria-describedby="brands-feedback">
                     <div class="invalid-feedback" id="brands-feedback"></div>
                  </div>
                  <div class="mb-3">
                     <textarea name="br_descr" id="br_descr" class="form-control ck-content"
                        placeholder="Description..." aria-describedby="br_descr-feedback"></textarea>
                     <div class="invalid-feedback" id="br_descr-feedback"></div>
                  </div>
                  <div class="mb-3 form-check">
                     <input type="checkbox" class="form-check-input" id="status" name="status" value="on">
                     <label for="status" class="form-check-label">Active</label>
                  </div>
                  <div class="mb-3 justify-content-between">
                     <input type="submit" name="submitBtn" id="submitBtn" value="Submit" class="button">
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>

</main>
<?php $this->end(); ?>
<?php $this->start('footer') ?>
<!----------custom--------->
<?= $this->asset('js/custom/backend/admin/products/brands', 'js') ?? ''?>
<?php $this->end();