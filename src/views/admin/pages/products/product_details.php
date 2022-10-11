<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Costum-------->
<link
    href="<?= $this->asset('css/custom/backend/admin/products/product_details', 'css') ?? ''?>"
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
                <li class="breadcrumb-item active" aria-current="page">Product Detail</li>
            </ol>
        </nav>
        <div class="d-flex header justify-content-between mb-4">
            <h1 class="header-title h3">
                <i class="fad fa-star-half-alt text-highlight"></i>
                Product Detail
            </h1>
        </div>
        <div class="card p-5">
            <div class="row">
                <!-- begin col product-image -->
                <div class="col-lg-6 col-md-6 mb-4 mb-md-0">
                    <div class="product-image-box text-center mb-4 p-3 rounded">
                        <img id="product-img"
                            src="<?=$this->asset('img/products/product-540x60.jpg')?>"
                            class="img-fluid" alt="Product Image">
                    </div>
                    <!-- begin product-gallery -->
                    <div class="product-gallery d-flex justify-content-center">
                        <a href="javascript: void(0);" class="product-gallery-item">
                            <img src="<?=$this->asset('img/products/product-540x60.jpg')?>"
                                class="img-fluid img-thumbnail p-2" style="max-width: 60px;" alt="Product-img">
                        </a>
                        <a href="javascript: void(0);" class="product-gallery-item ms-2">
                            <img src="<?=$this->asset('img/products/product-540x60.jpg')?>"
                                class="img-fluid img-thumbnail p-2" style="max-width: 60px;" alt="Product-img">
                        </a>
                        <a href="javascript: void(0);" class="product-gallery-item ms-2">
                            <img src="<?=$this->asset('img/products/product-540x60.jpg')?>"
                                class="img-fluid img-thumbnail p-2" style="max-width: 60px;" alt="Product-img">
                        </a>
                        <a href="javascript: void(0);" class="product-gallery-item ms-2">
                            <img src="<?=$this->asset('img/products/product-540x60.jpg')?>"
                                class="img-fluid img-thumbnail p-2" style="max-width: 60px;" alt="Product-img">
                        </a>
                    </div>
                    <!-- end product-gallery -->
                </div>
                <!-- end col product-image -->
                <!-- begin col product-detail -->
                <div class="col-lg-6 col-md-6">
                    <!-- begin product-detail -->
                    <div class="product-detail">
                        <h3 class="product-title">T-Shirt Form Girls</h3>
                        <div class="product-price mb-2">
                            <span class="price text-highlight fs-lg fw-700">$24.65</span>
                            <del>$29.00</del>
                            <div class="text-success d-inline">
                                <span>15% Off</span>
                            </div>
                        </div>
                        <!-- end product-price -->
                        <div class="rating-wrap">
                            <div class="rating">
                                <div class="product-rate" style="width:86%"></div>
                            </div>
                            <span class="rating-num">(21)</span>
                        </div>
                        <!-- end rating-wrap -->
                        <div class="product-desc mt-2">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas quis enim volutpat,
                                interdum odio
                                vel, sagittis massa.</p>
                        </div>
                        <div class="product-sort-info">
                            <ul class="list-unstyled">
                                <li><i class="fal fa-shield-check text-highlight"></i> If you're not 100% satisfied with
                                    your physical
                                    order, let us know and we'll make it right.</li>
                                <li><i class="fal fa-sync text-highlight"></i> 30 Day Return Policy</li>
                                <li><i class="fal fa-sack-dollar text-highlight"></i> Cash on Delivery available</li>
                            </ul>
                        </div>
                        <!-- end product-sort-info -->
                        <div class="product-switch-wrap clearfix">
                            <strong class="switch-label">Color</strong>
                            <ul class="product-color-list">
                                <li class="product-color-list-item ">
                                    <div class="product-color-block" style="background-color:#3f83dc"></div>
                                </li>
                                <li class="product-color-list-item ">
                                    <div class="product-color-block" style="background-color:#e54252"></div>
                                </li>
                                <li class="product-color-list-item ">
                                    <div class="product-color-block" style="background-color:#222222"></div>
                                </li>
                                <li class="product-color-list-item ">
                                    <div class="product-color-block" style="background-color:#898989"></div>
                                </li>
                                <li class="product-color-list-item ">
                                    <div class="product-color-block" style="background-color:#595b43"></div>
                                </li>
                            </ul>
                        </div>
                        <!-- end product-switch-wrap -->
                        <div class="product-switch-wrap clearfix">
                            <strong class="switch-lable">Size</strong>
                            <ul class="product-size-switch">
                                <li class="product-size-list-item active">
                                    <div class="product-size-block">xs</div>
                                </li>
                                <li class="product-size-list-item">
                                    <div class="product-size-block">s</div>
                                </li>
                                <li class="product-size-list-item">
                                    <div class="product-size-block">m</div>
                                </li>
                                <li class="product-size-list-item">
                                    <div class="product-size-block">l</div>
                                </li>
                                <li class="product-size-list-item">
                                    <div class="product-size-block">xl</div>
                                </li>
                            </ul>
                        </div>
                        <!-- end product-switch-wrap -->
                    </div>
                    <!-- end product-detail -->
                    <hr>
                    <ul class="product-meta list-unstyled">
                        <li>SKU: <a class="fw-700" href="#">AC31RFT</a></li>
                        <li>Category: <a class="fw-700" href="#">Classic Pullover Hoodie</a></li>
                        <li>Tags: <a href="#" class="badge bg-highlight rounded">Cloth</a> <a href="#"
                                class="badge bg-highlight rounded">printed</a> <a href="#"
                                class="badge bg-highlight rounded">T-shirt</a> </li>
                    </ul>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <h5 class="fw-700">Available Stock:</h5>
                            <p class="text-sm lh-150">2,986</p>
                        </div>
                        <div class="col-md-4">
                            <h5 class="fw-700">Number of Orders:</h5>
                            <p class="text-sm lh-150">6,196</p>
                        </div>
                        <div class="col-md-4">
                            <h5 class="fw-700">Revenue:</h5>
                            <p class="text-sm lh-150">$10,986,341</p>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
                <!-- end col product-detail -->
            </div>
            <!-- end row -->
            <div class="row">
                <div class="col-12">
                    <div class="tab-style3">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" id="Description-tab" data-bs-toggle="tab" href="#Description"
                                    role="tab" aria-controls="Description" aria-selected="false">Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="Additional-info-tab" data-bs-toggle="tab"
                                    href="#Additional-info" role="tab" aria-controls="Additional-info"
                                    aria-selected="false">Additional info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" id="Reviews-tab" data-bs-toggle="tab" href="#Reviews"
                                    role="tab" aria-controls="Reviews" aria-selected="true">Reviews (2)</a>
                            </li>
                        </ul>
                        <div class="tab-content shop-info-tab">
                            <div class="tab-pane fade" id="Description" role="tabpanel"
                                aria-labelledby="Description-tab">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas quis enim volutpat,
                                    interdum odio
                                    vel, sagittis massa. Phasellus id turpis leo. Aenean pretium non massa a ultricies.
                                    Integer quis
                                    nulla quis nulla mollis vestibulum. Nullam erat nulla, elementum placerat augue in,
                                    ultrices aliquet
                                    tellus. Mauris suscipit orci at mauris consequat, vel posuere nibh vestibulum.
                                    Vestibulum sit amet
                                    turpis dolor. Praesent commodo nibh sed mi facilisis, vitae auctor felis semper.
                                    Suspendisse
                                    potenti. Aenean tempor, quam sit amet hendrerit euismod, enim tellus aliquam dolor,
                                    quis tristique
                                    tortor ex eu leo. Duis ligula eros, maximus non mattis sed, vehicula feugiat urna.
                                    Ut faucibus
                                    hendrerit eleifend. Aenean ipsum velit, mollis sit amet felis ut, interdum luctus
                                    nibh.</p>
                                <p>Nam placerat justo vitae porttitor euismod. Nullam sit amet enim id elit varius
                                    vestibulum ac ac
                                    tortor. Nunc non tortor vitae nulla laoreet finibus. Curabitur at sagittis neque.
                                    Sed rutrum ante
                                    sem. Praesent mattis justo quis congue convallis. In at molestie odio, a porttitor
                                    ipsum. Duis
                                    elementum, augue vitae semper mollis, nunc ex vulputate diam, et luctus diam urna ut
                                    sapien. Lorem
                                    ipsum dolor sit amet, consectetur adipiscing elit. Maecenas pharetra tortor eget sem
                                    vehicula, a
                                    mollis dui molestie. Suspendisse sagittis lectus in velit tristique, vel luctus odio
                                    ornare. Ut dui
                                    lorem, hendrerit sed aliquet non, eleifend eu ex. Mauris vel purus vulputate,
                                    consequat velit non,
                                    tempus enim. Mauris nibh nunc, consequat vulputate massa non, vehicula imperdiet
                                    leo. Quisque
                                    gravida justo a enim blandit, in egestas velit varius.</p>
                            </div>
                            <div class="tab-pane fade" id="Additional-info" role="tabpanel"
                                aria-labelledby="Additional-info-tab">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>Capacity</td>
                                            <td>0.2 Kg</td>
                                        </tr>
                                        <tr>
                                            <td>Color</td>
                                            <td>Black, Brown, Red,</td>
                                        </tr>
                                        <tr>
                                            <td>Water Resistant</td>
                                            <td>Yes</td>
                                        </tr>
                                        <tr>
                                            <td>Material</td>
                                            <td>Heathered, poly/cotton/rayon blend</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade active show" id="Reviews" role="tabpanel"
                                aria-labelledby="Reviews-tab">
                                <div class="comments">
                                    <h4 class="product-tab-title">2 Review For <span>T-Shirt Form Girls</span></h4>
                                    <ul class="list-none comment-list mt-4">
                                        <li class="d-flex flex-row">
                                            <div class="comment-img me-3">
                                                <img src="<?=$this->asset('img/users/avatar.png')?>"
                                                    style="height:60px" alt="User" class="rounded">
                                            </div>
                                            <div class="comment-block">
                                                <div class="rating-wrap float-end">
                                                    <div class="rating">
                                                        <div class="product-rate" style="width:80%"></div>
                                                    </div>
                                                </div>
                                                <div class="customer-meta">
                                                    <span class="review-author fw-700 d-block">Schirsten Vander</span>
                                                    <span class="comment-date"><i>March 5, 2020</i></span>
                                                </div>
                                                <div class="description">
                                                    <p>Duis interdum gravida erat eu congue. Aenean malesuada sapien
                                                        risus, sed lacinia dui
                                                        sodales venenatis. Sed vitae felis vitae dolor consequat semper.
                                                        Vestibulum hendrerit
                                                        nulla justo, et hendrerit nibh accumsan et. Sed faucibus erat id
                                                        eleifend accumsan.</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="d-flex flex-row">
                                            <div class="comment-img me-3">
                                                <img src="<?=$this->asset('img/users/default-female-avatar.jpg')?>"
                                                    style="height:60px" alt="" class="rounded">
                                            </div>
                                            <div class="comment-block">
                                                <div class="rating-wrap float-end">
                                                    <div class="rating">
                                                        <div class="product-rate" style="width:60%"></div>
                                                    </div>
                                                </div>
                                                <div class="customer-meta">
                                                    <span class="review-author fw-700 d-block">Robert Nordic</span>
                                                    <span class="comment-date"><i>Apr 5, 2020</i></span>
                                                </div>
                                                <div class="description">
                                                    <p>Praesent tristique ex sit amet ante sodales, vel dignissim nisl
                                                        laoreet. Nunc sit amet
                                                        orci dolor. Duis sit amet porttitor diam. Sed nisi sapien,
                                                        lobortis eget lacus vitae,
                                                        tincidunt dapibus mauris.</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
    </div>

    <!-- Fin Content -->
    <input type="hidden" id="ip_address" style="display:none"
        value="<?=H_visitors::getIP()?>">
</main>
<?php $this->end(); ?>
<?php $this->start('footer') ?>
<!----------custom--------->
<script type="text/javascript"
    src="<?= $this->asset('js/custom/backend/admin/products/product_details', 'js') ?? ''?>">
</script>
<?php $this->end();
