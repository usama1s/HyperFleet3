<!-- 
<template>
   <table id="manage_bookings" style="width:100% background:#fff;" class="table display responsive nowrap">
    <thead>
        <tr>
            <th><input type="checkbox" id="all-checkbox-seleted-otp"></th>
            <th>Pickup Date</th>
            <th>Pickup Time</th>
            <th>Pickup Address</th>
            <th>Drop off/Duration</th>
            <th>Customer Name</th>
            <th>Customer Contact</th>
            <th>No. of Adults</th>
            <th>No. of Bags</th>
            <th>Vehicle Class</th>
            <th>Price</th>
            <th>Vehicle</th>
            <th >Driver</th>
            <th>source</th>  
            <th>Status</th>
          
        </tr>
    </thead>
    <tbody>

        <tr v-for="row in data">
            <td><input type="checkbox"></td>
            <td>{{row.booking.pickup_date}}</td>
            <td>{{row.booking.pickup_time}}</td>
            <td>{{row.booking.pickup_point}}</td>
            <td >
                <span v-if="row.booking.drop_off">
                    {{row.booking.drop_off}}
                </span>
                 <span v-if="row.booking.duration">
                    {{row.booking.duration}} Hours
                </span>
            </td>
            <td>{{row.booking.first_name}} {{row.booking.last_name}}</td>
            <td>{{row.booking.contact_no}}</td>
            <td>{{row.booking.no_of_adults}}</td>
            <td>{{row.booking.no_of_bags}}</td>
            <td>{{row.vclass.name}}</td>
            <td>{{row.booking.price}}</td>
            <td>
                <span v-if="(row.vehicle != null)">
                    {{row.vehicle.license_plate}}
                </span>

                <span v-else="">
                    No Vechicle
                </span>
            
            </td>
            <td>
                <span v-if="(row.driver != null )">
                    {{row.driver.first_name}} {{row.driver.last_name}}
                </span>

                 <span v-else="">
                    <a v-bind:href="'bookings/assignDriverToBooking/'+row.booking.id">Assign Driver</a>
                </span>

            </td>  
            <td>
                  <span v-if="row.supplier != null">
                        {{row.supplier.first_name}} {{row.supplier.last_name}}
                  </span>

                  
                 <span v-else="">
                    Admin
                </span>

            </td>  
            <td>{{row.booking.status}}</td>
        </tr>

        

    </tbody>

    
</table>


</template>

<script>
    export default {

        props: ['bookingsinput'],

        data() {
            return {
                data : []
            }
        },
        mounted() {
            this.data = JSON.parse(this.bookingsinput)
           // console.log(this.bookingsinput)
        },
 
    }
</script>

-->
