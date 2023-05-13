 {{-- Accounts Permissions --}}
 <div class="col-sm-4">
     <div class="well">
         <h4>Accounts</h4>
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
                                     <input type="checkbox" class="custom-control-input" id="accounts-create"
                                         slug="accounts" p-type="create" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="accounts-create"></label>
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
                                     <input type="checkbox" class="custom-control-input" id="accounts-edit"
                                         slug="accounts" p-type="edit" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="accounts-edit"></label>
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
                                     <input type="checkbox" class="custom-control-input" id="accounts-view"
                                         slug="accounts" p-type="view" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="accounts-view"></label>
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
                                     <input type="checkbox" class="custom-control-input" id="accounts-delete"
                                         slug="accounts" p-type="delete" onchange="updatePermission(this)">
                                     <label class="custom-control-label" for="accounts-delete"></label>
                                 </div>
                             </div>
                         </td>
                     </tr>

                 </tbody>
             </table>

         </div>
     </div>
 </div>
 {{-- Accounts Permissions End--}}