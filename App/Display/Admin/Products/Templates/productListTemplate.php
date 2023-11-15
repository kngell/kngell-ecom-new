<tr>
   <td>
      <label class="custom-checkbox">
         <input type="checkbox">
         <span></span>
      </label>
   </td>
   <td>
      <a href="ecommerce-product-detail.html">
         <img class="img-thumbnail" alt="Product" src="{{image}}" width="48">
      </a>
   </td>
   <td><a href="ecommerce-product-detail.html">{{title}}</a></td>
   <td>{{categorie}}</td>
   <td>{{price}}</td>
   <td class="text-center">{{qty}}</td>
   <td class="text-center">{{qty_sold}}</td>
   <td><span class="badge bg-success rounded">Active</span></td>
   <td>
      <ul class="list-unstyled table-actions">
         <li>
            {{form_edit}}
         </li>
         <li><a href="#">
               <i class="fa-solid fa-gear" data-bs-original-title="Settings" data-bs-toggle="tooltip"></i></a></li>
         <li><a href="#">
               <i class="fa-regular fa-chart-bar" data-bs-original-title="Analytics" data-bs-toggle="tooltip"></i>
            </a></li>
         <li><a href="#">
               <i class="fa-regular fa-clone" data-bs-original-title="Duplicate" data-bs-toggle="tooltip"></i>
            </a></li>
         <li>
            {{form_delete}}
         </li>
      </ul>
   </td>
</tr>