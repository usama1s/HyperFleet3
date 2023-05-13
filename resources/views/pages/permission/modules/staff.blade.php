 {{-- Staff Permissions --}}
 <div class="col-sm-4">
     <div class="well">
         <h4>Staff</h4>
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
                                     <input type="checkbox" class="custom-control-input" id="staff-create" slug="staff"
                                         p-type="create" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="staff-create"></label>
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
                                     <input type="checkbox" class="custom-control-input" id="staff-edit" slug="staff"
                                         p-type="edit" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="staff-edit"></label>
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
                                     <input type="checkbox" class="custom-control-input" id="staff-view" slug="staff"
                                         p-type="view" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="staff-view"></label>
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
                                     <input type="checkbox" class="custom-control-input" id="staff-delete" slug="staff"
                                         p-type="delete" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="staff-delete"></label>
                                 </div>
                             </div>
                         </td>
                     </tr>

                 </tbody>
             </table>

         </div>
     </div>
 </div>
 {{-- Staff Permissions End--}}