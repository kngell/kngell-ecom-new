<div class="modal fade" id="add_address-box" tabindex="-1" aria-labelledby="Add Address" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="default_delivery_address__title">Adresse de livraison</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            {{form_begin}}
            {{addAddressContent}}
            {{form_end}}
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" form="add-address-frm">Save changes</button>
         </div>
      </div>
   </div>
</div>