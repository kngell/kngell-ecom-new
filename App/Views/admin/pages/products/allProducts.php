<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Costum-------->
<?= $this->asset('css/admin/pages/products/products', 'css') ?? ''?>
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<main id="main" class="main-container">
   <div class="main-head">
      <nav aria-label="breadcrumb">
         <ol class="breadcrumb ps-0 fs-base">
            <li class="breadcrumb-item"><a href="#">FlexAdmin</a></li>
            <li class="breadcrumb-item"><span>Ecommerce</span></li>
            <li class="breadcrumb-item active" aria-current="page">Products</li>
         </ol>
      </nav>
      <div class="row justify-content-between mb-4">
         <div class="col-12">
            <h1 class="header-title h3">
               <i class="fa-solid fa-star-half-stroke text-highlight"></i>
               Products
            </h1>
         </div>
      </div>
   </div>
   <div class="main-body">
      <div class="row mb-4">
         <div class="col-12">
            <div class="input-group">
               <button class="btn btn-highlight" type="button" data-bs-toggle="modal" data-bs-target="#modal-box"><i
                     class="fa-solid fa-circle-plus"></i><span class="d-none d-md-inline ms-1">Add
                     Product</span></button>
               <button class="btn btn-highlight" type="button"><i class="fa-solid fa-file-import"></i> <span
                     class="d-none d-md-inline ms-1">Import</span></button>
               <button class="btn btn-highlight" type="button"><i class="fa-solid fa-file-arrow-down"></i> <span
                     class="d-none d-md-inline ms-1">Export</span></button>
               <button class="btn btn-highlight" type="button"><i class="fa-solid fa-list-ul"></i> <span
                     class="d-none d-md-inline ms-1">Customize Columns</span></button>
               <button type="button" class="btn btn-highlight dropdown-toggle dropdown-toggle-split"
                  data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa-solid fa-pen"></i> <span class="d-none d-md-inline ms-1">Bulk Actions</span>
               </button>
               <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Delete</a></li>
                  <li><a class="dropdown-item" href="#">Update Statuses</a></li>
                  <li><a class="dropdown-item" href="#">Create Notes</a></li>
               </ul>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-12">
            <div class="card">
               <div class="card-body">
                  <div class="table-responsive" id="showAll">
                     <table id="ecommerce-datatable" class="table table-middle table-hover table-responsive">
                        <thead>
                           <tr>
                              <th class="no-sort">
                                 <label class="custom-checkbox">
                                    <input type="checkbox">
                                    <span></span>
                                 </label>
                              </th>
                              <th class="no-sort">Image</th>
                              <th>Name</th>
                              <th>Category</th>
                              <th>Price</th>
                              <th class="text-center">Quantity</th>
                              <th class="text-center">Units Sold</th>
                              <th>Status</th>
                              <th class="text-center no-sort">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?= $productList ?? ''?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <section class="modals">
      <div class="modal fade" role="dialog" id="modal-box">
         <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title"> Add new</h5>
                  <button type="button" class="btn-close text-light" data-bs-dismiss="modal"></button>
               </div>
               <div class="modal-body">
                  <?=$product_form ?? ''?>
               </div>
            </div>
         </div>
      </div>
   </section>
   <section class="addtionnal-frm">
      <?=$additionnal_forms ?? ''?>
   </section>
</main>
<!----------Add new  Modal-------->
<?php $this->end(); ?>
<?php $this->start('footer') ?>
<!----------custom--------->
<?= $this->asset('js/admin/pages/products/products', 'js') ?? ''?>
<?php $this->end();