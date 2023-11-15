{{form_begin}}
{{form_input_hidden}}
<div class="row">
   <div class="col-lg-8">
      {{product_infos}}
      <!-- end card -->
      {{product_media}}
      <!-- end card -->
      {{product_pricing}}
      <!-- end card -->
      {{product_inventory}}
      <!-- end card -->
      {{product_shipping}}
      <!-- end card -->
      {{product_variants}}
      <!-- end card -->
   </div>
   <!-- end col -->
   <div class="col-lg-4">
      {{product_organization}}
      <!-- end card -->
      {{product_categories}}
      <!-- end card -->
      {{product_tags}}
      <!-- end card -->
      <div class="card">
         <div class="card-body d-flex justify-content-between">
            <button class="btn btn-outline-highlight" type="button">Save Draft</button>
            <button class="btn btn-highlight" id="save-all" type="submit"><i class="fa-regular fa-floppy-disk"></i>
               Save</button>
         </div>
         <!-- end card-body -->
      </div>
      <!-- end card -->
   </div>
   <!-- end col -->
</div>
<!-- </form> -->
{{form_end}}