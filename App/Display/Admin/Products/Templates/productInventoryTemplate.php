<div class="card">
   <div class="card-body">
      <h4>Inventory</h4>
      <div class="row">
         <div class="col-lg-6">
            {{sku_stock}}
         </div>
         <!-- end col -->
         <div class="col-lg-6">
            {{code_bar}}
         </div>
         <!-- end col -->
      </div>
      <!-- end row -->
      <div class="row border-bottom mb-3 pb-3">
         <div class="col-12">
            {{track_qty}}
         </div>
         <div class="col-12">
            {{self_when_out_of_stock}}
         </div>
      </div>
      <!-- end row -->
      <h5 class="mt-3">QUANTITY</h5>
      <div class="row">
         <div class="col-lg-6">
            {{stock_quantity}}
         </div>
         <!-- end col -->
         <div class="col-lg-6">
            {{allow_backorder}}
         </div>
         <!-- end col -->
      </div>
      <!-- end row -->
      <div class="row">
         <div class="col-lg-6">
            {{low_stock}}
         </div>
         <div class="col-lg-6">
            {{product_units}}
         </div>
         <!-- end col -->
      </div>
      <!-- end row -->
   </div>
   <!-- end card-body -->
</div>