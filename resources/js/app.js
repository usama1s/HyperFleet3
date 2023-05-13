/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('manage-booking-component', require('./components/ManageBookingComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app',
// });



// Echo.join('chat')
//     .here((user) => {
//         console.log(user);
//     })
//     .joining((user) => {
//         console.log(user);
//     })
//     .leaving((user) => {
//         console.log(user);
//     });


let userID = document.head.querySelector('meta[name="user-id"]').content;
let csrf = document.head.querySelector('meta[name="csrf-token"]').content;




Echo.private('App.User.' + userID)
    .notification((notification) => {
      
     

        var actionbtns =  '';
        
        if(notification.notifytype=='newbooking'){
            url = rootPath+"driver-bookingaction";
            actionbtns = '<form action="'+url+'" method="POST"><input type="hidden" name="_token" value="'+csrf+'"><input  type="hidden" name="booking_id" value="'+ notification.booking_id.id+'"><input class="btn btn-sm btn-success" type="submit" name="accept" value="accept"><input class="btn btn-sm btn-danger" type="submit" name="reject" value="reject"></form>';
        }

       

        $(document).Toasts('create', {
            title: notification.subject,
            body: notification.msg+actionbtns,//'<br><a href="#" class="btn btn-sm btn-success">Accept</a><a href="#" class="btn btn-sm btn-danger">Reject</a>',
            autohide:true,
            delay:4000,
            class: "bg-info",
            icon: 'fas fa-envelope fa-lg'
          });

          var divider = '<div class="dropdown-divider"></div>';
          //var acceptbtn = '<span class="text-success">Accept </span> ';
          //var rejectbtn = ' <span class="text-danger"> Reject</span>';
         // var actionbtns = ' <div class="action-btns">'+acceptbtn+rejectbtn+'</div>';
         
    
     
    
          var content = '<a href="'+notification.link+'" class="dropdown-item single-notification unread_notification"><i class="fas fa-envelope mr-2"></i>'+notification.subject+'<span class="float-right text-muted text-sm">1 seconds ago</span></a>';
          $("#nofifications_wapper").prepend(divider+content);
          $('.notification_count').text(notification.count + 1);

          notificationSound();
    });

   


