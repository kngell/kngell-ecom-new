<?php declare(strict_types=1);
$this->start('head'); ?>
<!-------Costum-------->
<?= $this->asset('css/custom/backend/admin/users/profile', 'css') ?? ''?>
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<main id="main" class="main-container">
   <nav aria-label="breadcrumb">
      <ol class="breadcrumb ps-0 fs-base">
         <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
         <li class="breadcrumb-item"><span>Users</span></li>
         <li class="breadcrumb-item active" aria-current="page">Profile</li>
      </ol>
   </nav>
   <div class="header justify-content-between mb-4">
      <h1 class="header-title h3">
         <i class="fad fa-star-half-alt text-highlight"></i>
         User Profile
      </h1>
   </div>
   <div class="row mb-4">
      <div class="col-12">
         <div class="card card-solid">
            <h5 class="card-header d-flex">
               <span class="text-light lead">User profile</span>
               <span class="ms-auto"> <a href="javascript:history.go(-1)" class="btn btn-light btn-secondary"
                     id="back"><i class="far fa-arrow-alt-circle-left fa-lg"></i></i>&nbsp;Back
                  </a>&nbsp;
               </span>
            </h5>
            <div class="row">
               <div class="col-md-4 col-xl-3">
                  <div class="card mb-3">
                     <div class="card-header">
                        <h5 class="card-title mb-0">Profile Details</h5>
                     </div>
                     <div class="card-body text-center">
                        <img src="/public/assets/img/avatar.png" alt="Christina Mason"
                           class="img-fluid rounded-circle mb-2 p-image" />
                        <h5 class="card-title mb-0">Christina Mason</h5>
                        <div class="text-muted mb-2">Lead Developer</div>

                        <div>
                           <a class="btn btn-primary btn-sm" href="#">Follow</a>
                           <a class="btn btn-primary btn-sm" href="#"><span data-feather="message-square"></span>
                              Message</a>
                        </div>
                     </div>
                     <hr class="my-0" />
                     <div class="card-body">
                        <h5 class="h6 card-title">Skills</h5>
                        <a href="#" class="badge bg-primary mr-1 my-1">HTML</a>
                        <a href="#" class="badge bg-primary mr-1 my-1">JavaScript</a>
                        <a href="#" class="badge bg-primary mr-1 my-1">Sass</a>
                        <a href="#" class="badge bg-primary mr-1 my-1">Angular</a>
                        <a href="#" class="badge bg-primary mr-1 my-1">Vue</a>
                        <a href="#" class="badge bg-primary mr-1 my-1">React</a>
                        <a href="#" class="badge bg-primary mr-1 my-1">Redux</a>
                        <a href="#" class="badge bg-primary mr-1 my-1">UI</a>
                        <a href="#" class="badge bg-primary mr-1 my-1">UX</a>
                     </div>
                     <hr class="my-0" />
                     <div class="card-body">
                        <h5 class="h6 card-title">About</h5>
                        <ul class="list-unstyled mb-0">
                           <li class="mb-1"><span data-feather="home" class="feather-sm mr-1"></span> Lives
                              in
                              <a href="#">San Francisco, SA</a>
                           </li>

                           <li class="mb-1"><span data-feather="briefcase" class="feather-sm mr-1"></span>
                              Works at <a href="#">GitHub</a></li>
                           <li class="mb-1"><span data-feather="map-pin" class="feather-sm mr-1"></span>
                              From
                              <a href="#">Boston</a>
                           </li>
                        </ul>
                     </div>
                     <hr class="my-0" />
                     <div class="card-body">
                        <h5 class="h6 card-title">Elsewhere</h5>
                        <ul class="list-unstyled mb-0">
                           <li class="mb-1"><span class="fas fa-globe fa-fw mr-1"></span> <a href="#">staciehall.co</a>
                           </li>
                           <li class="mb-1"><span class="fab fa-twitter fa-fw mr-1"></span> <a href="#">Twitter</a>
                           </li>
                           <li class="mb-1"><span class="fab fa-facebook fa-fw mr-1"></span> <a href="#">Facebook</a>
                           </li>
                           <li class="mb-1"><span class="fab fa-instagram fa-fw mr-1"></span> <a href="#">Instagram</a>
                           </li>
                           <li class="mb-1"><span class="fab fa-linkedin fa-fw mr-1"></span> <a href="#">LinkedIn</a>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
               <div class="col-md-8 col-xl-9">
                  <div class="card mb-3">
                     <div class="card-header">
                        <h5 class="card-title mb-0 text-center">Update account</h5>
                     </div>
                     <div class="card-body">
                        <nav>
                           <div class="nav nav-tabs" id="nav-tab" role="tablist">
                              <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                 data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                 aria-selected="true">Mes commandes</button>
                              <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                 data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                                 aria-selected="false">Mon profil</button>
                              <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                 data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                 aria-selected="false">Moyens de
                                 payement</button>
                           </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                           <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                              aria-labelledby="nav-home-tab">

                           </div>
                           <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                              aria-labelledby="nav-profile-tab">
                              <form action="" novalidate>
                                 <div class="row g-3">
                                    <div class="col-sm-6">
                                       <label for="firstName" class="form-label">First Name</label>
                                       <input type="text" class="form-control" id="firstName" name="firstName"
                                          placeholder="John">
                                       <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-sm-6">
                                       <label for="lastName" class="form-label">Last Name</label>
                                       <input type="text" class="form-control" id="lastName" name="lastName"
                                          placeholder="Smith">
                                       <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-12">
                                       <label for="userName" class="form-label">Username</label>
                                       <div class="input-group">
                                          <span class="input-group-text">@</span>
                                          <input type="text" class="form-control" id="userName" name="userName"
                                             placeholder="jsmith">
                                       </div>
                                       <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-5">
                                       <label for="country" class="form-label">Country</label>
                                       <select class="form-control" id="country" name="country">
                                          <option value="">Choose...</option>
                                          <option value="Australia">Autralia</option>
                                          <option value="Australia">USA</option>
                                          <option value="Australia">China</option>
                                       </select>
                                       <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-4">
                                       <label for="region" class="form-label">Region</label>
                                       <select class="form-control" id="region" name="region">
                                          <option value="">Choose...</option>
                                          <option value="Australia">IDF</option>
                                          <option value="Australia">Vaucluse</option>
                                          <option value="Australia">Val d4oise</option>
                                       </select>
                                       <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-3">
                                       <label for="postcode" class="form-label">Code postal</label>
                                       <input type="number" class="form-control" id="postcode" name="postcode"
                                          placeholder="95000">
                                       <div class="invalid-feedback"></div>
                                    </div>
                                 </div>
                                 <hr class="my-4">
                                 <div class="col-12">
                                    <div class="form-check">
                                       <input type="checkbox" class="form-check-input" id="sameAddress"
                                          name="sameAddress">
                                       <label for="sameAddress" class="form-check-label">Shipping
                                          address
                                          is the
                                          same
                                          as billing.</label>
                                       <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-check">
                                       <input type="checkbox" class="form-check-input" id="saveInfos"
                                          name="sameAddress">
                                       <label for="saveInfos" class="form-check-label">Save these
                                          infos.</label>
                                       <div class="invalid-feedback"></div>
                                    </div>
                                 </div>
                              </form>
                           </div>
                           <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                              aria-labelledby="nav-contact-tab">...</div>
                        </div>

                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      </section>
   </div>
</main>
<?php $this->end(); ?>
<?php $this->start('footer') ?>
<!----------custom--------->
<?= $this->asset('js/custom/backend/admin/users/profile', 'js') ?? ''?>
<?php $this->end();