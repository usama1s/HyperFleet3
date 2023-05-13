 {{-- Drivers Permissions --}}
 <div class="col-sm-4">
     <div class="well">
         <h4>Vehicle</h4>
         <div id="sub_data10">
             <table class="table table-condensed">
                 <tbody>
                     <tr>
                         <th style="width: 100%;">Privilege</th>
                         <th></th>
                         <th>Action</th>
                     </tr>
                     <tr>
                         <td>Add</td>
                         <td></td>
                         <td>
                             <div class="form-group">
                                 <div class="custom-control custom-switch">
                                     <input type="checkbox" class="custom-control-input" id="vehicle-create"
                                         slug="vehicle" p-type="create" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="vehicle-create"></label>
                                 </div>
                             </div>
                         </td>
                     </tr>

                     <tr>
                         <td>Edit</td>
                         <td></td>
                         <td>
                             <div class="form-group">
                                 <div class="custom-control custom-switch">
                                     <input type="checkbox" class="custom-control-input" id="vehicle-edit"
                                         slug="vehicle" p-type="edit" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="vehicle-edit"></label>
                                 </div>
                             </div>
                         </td>
                     </tr>

                     <tr>
                         <td>View</td>
                         <td></td>
                         <td>
                             <div class="form-group">
                                 <div class="custom-control custom-switch">
                                     <input type="checkbox" class="custom-control-input" id="vehicle-view"
                                         slug="vehicle" p-type="view" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="vehicle-view"></label>
                                 </div>
                             </div>
                         </td>
                     </tr>

                     <tr>
                         <td>Remove</td>
                         <td></td>
                         <td>
                             <div class="form-group">
                                 <div class="custom-control custom-switch">
                                     <input type="checkbox" class="custom-control-input" id="vehicle-delete"
                                         slug="vehicle" p-type="delete" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="vehicle-delete"></label>
                                 </div>
                             </div>
                         </td>
                     </tr>

                 </tbody>
             </table>

         </div>
     </div>
 </div>
 {{-- vehicles Permissions End--}}