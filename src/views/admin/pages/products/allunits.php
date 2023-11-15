<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Costum-------->
<meta name="csrftoken" content="<?=$this->token->generate_token(8, 'all_product_page')?>" />
<link href="<?= $this->asset('css/custom/backend/admin/products/allunits', 'css') ?? ''?>" rel="stylesheet"
    type="text/css">
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<main id=main-site>
    <!-- Content -->
    <div class="page-content">
        <!-- Content Header (Page header) -->
        <div class="row header justify-content-between mb-4 w-100">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb ps-0 fs-base">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item"><span>Products</span></li>
                        <li class="breadcrumb-item active" aria-current="page">All Units</li>
                    </ol>
                </nav>
            </div>
            <div class="col text-end">
                <h4 class="header-title h3">
                    Manage Units
                </h4>
            </div>
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <div class="row mb-4 content">
            <div class="col-12">
                <!-- Small boxes (Stat box) -->
                <div class="card border-primary">
                    <h5 class="card-header bg-primary d-flex">
                        <span class="text-light lead">Manage Units</span>
                        <span class="ms-auto"> <a href="javascript:history.go(-1)" class="btn btn-light btn-secondary"
                                id="back"><i class="far fa-arrow-alt-circle-left fa-lg"></i></i>&nbsp;Back
                            </a>&nbsp;&nbsp;
                            <a href="" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-box"
                                id="addNew"><i class="fas fa-plus-circle fa-lg"></i>&nbsp;Add new</a>
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
    </div><!-- /.container-fluid -->
    <!----------Add new categorie Modal-------->
    <div class="modal fade" role="dialog" id="modal-box">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Add new</h5>
                    <button type="button" class="btn-close text-light" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="post" id="add-units-frm" class="px-3 needs-validation" novalidate>
                        <?= FH::csrfInput('csrftoken', $this->token->generate_token(8, 'add-units-frm')); ?>
                        <input type="hidden" name="operation" id="operation">
                        <input type="hidden" name="unID" id="unID">
                        <input type="hidden" name="created_at" id="created_at">
                        <input type="hidden" name="updated_at" id="updated_at">
                        <div id="alertErr"></div>
                        <div class="select-box mb-3">
                            <select class="form-control unit" id="unit" name="unit">
                            </select>
                            <div class="invalid-feedback"></div>
                            <span class="custom-arrow"></span>
                        </div>
                        <div class="mb-3">
                            <textarea name="descr" id="descr" class="form-control ck-content"
                                placeholder="Description..."></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="status" name="status" value="on">
                            <label for="status" class="form-check-label">Active</label>
                        </div>
                        <div class="form-group justify-content-between">
                            <input type="submit" name="submitBtn" id="submitBtn" value="Submit" class="button">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Fin Content -->
    <input type="hidden" id="ipAddress" style="display:none" value="<?=H_visitors::getIP()?>">
</main>
<?php $this->end(); ?>
<?php $this->start('footer') ?>
<!----------custom--------->
<script type="text/javascript" src="<?= $this->asset('js/custom/backend/admin/products/allunits', 'js') ?? ''?>">
</script>
<?php $this->end();
