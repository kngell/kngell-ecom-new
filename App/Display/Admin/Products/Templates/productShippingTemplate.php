<div class="card">
   <div class="card-body">
      <h4 class="mb-3">Shipping</h4>
      <div class="row border-bottom mb-3 pb-3">
         <div class="col-lg-6">
            {{weight}}
         </div>
      </div>
      <div class="row border-bottom mb-3 pb-3">
         <label for="product-length" class="col-lg-2 col-form-label fw-700">Dimensions
            (cm)</label>
         <div class="col-lg-10">
            <div class="row">
               <div class="col-3">
                  {{length}}
               </div>
               <div class="col-3">
                  {{width}}
               </div>
               <div class="col-3">
                  {{height}}
               </div>
            </div>
         </div>
      </div>
      <!-- end row -->
      <div class="row">
         <div class="col-lg-6">
            {{shipping_class}}
         </div>
      </div>
   </div>
   <!-- end card-body -->
</div>