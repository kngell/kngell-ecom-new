<div class="card">
   <div class="card-body">
      <h4>Variants</h4>
      <!-- Loop true options -->
      <div class="row mb-3">
         <div class="form-group">
            <label for="p_variant_title">Option 1</label>
         </div>
         <!-- end form-group -->
         <div class="col-3">
            <input class="form-control" type="text" value="Size" name="p_variant_title" id="p_variant_title"
               form="product-variant-frm">
         </div>
         <!-- end col -->
         <div class="col-9">
            <input class="form-control" type="text" name="p_variant_value" id="p_variant_value"
               placeholder="Separate options with a comma" form="product-variant-frm">
         </div>
         <!-- end col -->
      </div>

      <!-- end row -->
      <button class="btn btn-sm btn-outline-highlight waves-effect" type="button" form="product-variant-frm"><i
            class="fa-solid fa-circle-plus"></i> Add
         another option</button>
   </div>
   <!-- end card-body -->
</div>