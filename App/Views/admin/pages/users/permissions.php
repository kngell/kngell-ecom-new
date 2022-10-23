<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Costum-------->
<?= $this->asset('css/custom/backend/admin/users/permissions', 'css') ?? ''?>
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<main id="main" class="main-container">
   <!-- Content Header (Page header) -->
   <div class="row header justify-content-between mb-4 w-100">
      <div class="col">
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb ps-0 fs-base">
               <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
               <li class="breadcrumb-item"><span>All users</span></li>
               <li class="breadcrumb-item active" aria-current="page">Groups and permissions</li>
            </ol>
         </nav>
      </div>
      <div class="col text-end">
         <h4 class="header-title h3">
            Manage Groups and permissions
         </h4>
      </div>
   </div>
   <!-- /.content-header -->
   <!-- Main content -->
   <div class="row mb-4 content">
      <div class="col-12">
         <!-- Small boxes (Stat box) -->
         <div class="card border-primary" id="content-box">
            <h5 class="card-header d-flex">
               <span class="text-light lead">Manage Groups and Permissions</span>
               <span class="ms-auto"> <a href="javascript:history.go(-1)" class="btn btn-light btn-secondary"
                     id="back"><i class="far fa-arrow-alt-circle-left fa-lg"></i></i>&nbsp;Back
                  </a>&nbsp;&nbsp;
                  <a href="" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-box" id="addNew"><i
                        class="fas fa-plus-circle fa-lg"></i>&nbsp;Add new</a>
               </span>
            </h5>
            <div id="globalErr"></div>
            <div class="card-body">
               <div class="table-responsive" id="showAll">
                  <p class="text-center lead mt-5">Please wait...</p>
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
               <h5 class="modal-title"> Add new</h5>
               <button type="button" class="btn-close text-light" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
               <form action="#" method="post" id="groups-and-permissions-frm" class="px-3 needs-validation">
                  <input type="hidden" name="operation" id="operation">
                  <input type="hidden" name="grID" id="grID">
                  <input type="hidden" name="date_enreg" id="date_enreg">
                  <input type="hidden" name="updateAt" id="updateAt">
                  <input type="hidden" name="deleted" id="deleted">
                  <div id="alertErr"></div>
                  <div class="form-group">
                     <input type="text" name="name" id="name" class="form-control " placeholder="Group name">
                     <div class="invalid-feedback"></div>
                  </div>
                  <div class="form-group">
                     <textarea name="description" id="description" class="form-control ck-content"
                        placeholder="Group description..."></textarea>
                     <div class="invalid-feedback"></div>
                  </div>
                  <div class="form-check">
                     <input type="checkbox" class="form-check-input" id="status" name="status" value="1">
                     <label for="status" class="form-check-label">Active</label>
                  </div>
                  <div class="form-group select-box">
                     <select class="form-select select_group alert" id="parentID" name="parentID"
                        aria-label="size 3 select example">
                        <option value="0"></option>
                     </select>
                     <span class="custom-arrow"></span>
                  </div>
                  <div class="form-group justify-content-between">
                     <input type="submit" name="submitBtn" id="submitBtn" value="Enregistrer" class="button">
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>

   <?php $this->end(); ?>
   <?php $this->start('footer') ?>
   <!----------custom--------->
   <?= $this->asset('js/custom/backend/admin/users/permissions', 'js') ?? ''?>
   <?php $this->end();