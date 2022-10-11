<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Costum-------->
<link
    href="<?= $this->asset('css/custom/admin/products/new_product', 'css') ?? ''?>"
    rel="stylesheet" type="text/css">
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<main id=main-site>
    <!-- Content -->
    <div class="page-content">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb ps-0 fs-base">
                <li class="breadcrumb-item"><a href="#">FlexAdmin</a></li>
                <li class="breadcrumb-item"><span>Ecommerce</span></li>
                <li class="breadcrumb-item active" aria-current="page">Add Product</li>
            </ol>
        </nav>
        <div class="header justify-content-between mb-4">
            <h1 class="header-title h3">
                <i class="fad fa-star-half-alt text-highlight"></i>
                Add Product
            </h1>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="product-title">Title <span class="text-danger">*</span></label>
                                    <input type="text" id="product-title" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="product-description">Description <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="product-description" rows="3"></textarea>
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->
                <div class="card">
                    <div class="card-body">
                        <h4>Media</h4>
                        <form
                            action="<?=PROOT . 'assets' . US . 'upload'?>"
                            class="dropzone" id="product-media">
                            <div class="dz-message needsclick">
                                <div class="mb-4">
                                    <button class="btn btn-highlight">
                                        <i class="fal fa-cloud-upload"></i>
                                        <span>Add file</span>
                                    </button>
                                </div>
                                <span class="text-uppercase">or drop files to upload.</span>
                                <br>
                                <span class="fs-sm text-muted">This is just a demo dropzone. Selected files are
                                    <strong>not</strong>
                                    actually uploaded.</span>
                            </div>
                        </form>
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->
                <div class="card">
                    <div class="card-body">
                        <h4>Pricing</h4>
                        <div class="row border-bottom mb-3">
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="product-price">Price</label>
                                    <input class="form-control" type="text" id="product-price">
                                </div>
                            </div>
                            <!-- end col -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="product-compare-price">Compare at price</label>
                                    <input class="form-control" type="text" id="product-compare-price">
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                        <div class="row mt-3">
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="product-cost-per-item">Cost per item</label>
                                    <input class="form-control" type="text" id="product-cost-per-item">
                                    <span class="help-block">
                                        <small>Customers won’t see this</small>
                                    </span>
                                </div>
                            </div>
                            <!-- end col -->
                            <div class="col-lg-6 d-flex align-items-center">
                                <label class="custom-checkbox">
                                    <input type="checkbox" checked="checked"> Charge tax on this product
                                    <span></span>
                                </label>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->
                <div class="card">
                    <div class="card-body">
                        <h4>Inventory</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="product-sku">SKU (Stock Keeping Unit)</label>
                                    <input class="form-control" type="text" id="product-sku">
                                </div>
                            </div>
                            <!-- end col -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="product-bar-code">Barcode (ISBN, UPC, GTIN, etc.)</label>
                                    <input class="form-control" type="text" id="product-bar-code">
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                        <div class="row border-bottom mb-3 pb-3">
                            <div class="col-12">
                                <label class="custom-checkbox">
                                    <input type="checkbox" checked="checked"> Track quantity
                                    <span></span>
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="custom-checkbox">
                                    <input type="checkbox" checked="checked"> Continue selling when out of stock
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <!-- end row -->
                        <h5 class="mt-3">QUANTITY</h5>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="product-quantity">Stock quantity</label>
                                    <input class="form-control" type="number" id="product-quantity">
                                </div>
                            </div>
                            <!-- end col -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="product-backorder">Allow backorder</label>
                                    <select class="form-select" id="product-backorder">
                                        <option selected>No not allow</option>
                                        <option>Allow, but notify customer</option>
                                        <option>Allow</option>
                                    </select>
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="product-threshold">Low stock threshold</label>
                                    <input class="form-control" type="number" id="product-threshold">
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Shipping</h4>
                        <div class="row border-bottom mb-3 pb-3">
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="product-weight">Weight (kg)</label>
                                    <input class="form-control" type="text" id="product-weight">
                                </div>
                            </div>
                        </div>
                        <div class="row border-bottom mb-3 pb-3">
                            <label for="product-length" class="col-lg-2 col-form-label fw-700">Dimensions (cm)</label>
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-3">
                                        <input class="form-control" type="text" id="product-length"
                                            placeholder="Length">
                                    </div>
                                    <div class="col-3">
                                        <input class="form-control" type="text" id="product-width" placeholder="Width">
                                    </div>
                                    <div class="col-3">
                                        <input class="form-control" type="text" id="product-height"
                                            placeholder="Height">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="product-shipping-class">Shipping Class</label>
                                    <select class="form-select" id="product-shipping-class">
                                        <option selected>No shipping class</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->
                <div class="card">
                    <div class="card-body">
                        <h4>Variants</h4>
                        <div class="row mb-3">
                            <div class="form-group">
                                <label for="product-variant-1">Option 1</label>
                            </div>
                            <!-- end form-group -->
                            <div class="col-3">
                                <input class="form-control" type="text" value="Size" id="product-variant-1">
                            </div>
                            <!-- end col -->
                            <div class="col-9">
                                <input class="form-control" type="text" id="value-product-variant-1"
                                    placeholder="Separate options with a comma">
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                        <div class="row mb-3">
                            <div class="form-group">
                                <label for="product-variant-2">Option 2</label>
                            </div>
                            <!-- end form-group -->
                            <div class="col-3">
                                <input class="form-control" type="text" value="Color" id="product-variant-2">
                            </div>
                            <!-- end col -->
                            <div class="col-9">
                                <input class="form-control" type="text" id="value-product-variant-2"
                                    placeholder="Separate options with a comma">
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                        <div class="row border-bottom pb-3 mb-3">
                            <div class="form-group">
                                <label for="product-variant-3">Option 3</label>
                            </div>
                            <!-- end form-group -->
                            <div class="col-3">
                                <input class="form-control" type="text" value="Style" id="product-variant-3">
                            </div>
                            <!-- end col -->
                            <div class="col-9">
                                <input class="form-control" type="text" id="value-product-variant-3"
                                    placeholder="Separate options with a comma">
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                        <button class="btn btn-sm btn-outline-highlight" type="button"><i
                                class="fal fa-plus-circle"></i> Add
                            another option</button>
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4>Organization</h4>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label for="product-type">Product type</label>
                                    <input class="form-control" type="text" id="product-type">
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label for="product-vendor">Vendor</label>
                                    <input class="form-control" type="text" id="product-vendor">
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->
                <div class="card">
                    <div class="card-body">
                        <h4>Product categories</h4>
                        <div>
                            <label class="custom-checkbox">
                                <input type="checkbox"> Leggings
                                <span></span>
                            </label>
                            <label class="custom-checkbox">
                                <input type="checkbox"> Pet Hoodie
                                <span></span>
                            </label>
                            <label class="custom-checkbox">
                                <input type="checkbox"> Unisex / Men's T-shirt
                                <span></span>
                            </label>
                            <label class="custom-checkbox">
                                <input type="checkbox"> Women's T-shirt
                                <span></span>
                            </label>
                        </div>
                        <button class="btn btn-sm btn-outline-highlight" type="button"><i
                                class="fal fa-plus-circle"></i> Add new
                            category</button>
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->
                <div class="card">
                    <div class="card-body">
                        <h4>Product tags</h4>
                        <form class="">
                            <div class="d-flex align-items-center">
                                <div class="form-group me-1 flex-grow-1">
                                    <input type="text" class="form-control" />
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-highlight">
                                    Add
                                </button>
                            </div>
                            <span class="fs-sm"><i>Separate tags with commas</i></span>
                            <div class="product-tags mt-2">
                                <span class="badge bg-highlight"><a href="javascript:void(0);"><i
                                            class="fal fa-times"></i></a>
                                    T-shirt</span>
                                <span class="badge bg-highlight"><a href="javascript:void(0);"><i
                                            class="fal fa-times"></i></a>
                                    Christmas</span>
                                <span class="badge bg-highlight"><a href="javascript:void(0);"><i
                                            class="fal fa-times"></i></a>
                                    Lorem</span>
                                <span class="badge bg-highlight"><a href="javascript:void(0);"><i
                                            class="fal fa-times"></i></a>
                                    Ipsum</span>
                            </div>
                        </form>
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->
                <div class="card">
                    <div class="card-body d-flex justify-content-between">
                        <button class="btn btn-outline-highlight" type="button">Save Draft</button>
                        <button class="btn btn-highlight" type="button"><i class="fal fa-save"></i> Save</button>
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>

    <!-- Fin Content -->
    <input type="hidden" id="ip_address" style="display:none"
        value="<?=H_visitors::getIP()?>">
</main>
<?php $this->end(); ?>
<?php $this->start('footer') ?>
<!----------custom--------->
<script type="text/javascript"
    src="<?= $this->asset('js/custom/admin/products/new_product', 'js') ?? ''?>">
</script>
<?php $this->end();
