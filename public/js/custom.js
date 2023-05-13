var rootPath = document.head.querySelector('meta[name="base-root-path"]')
    .content;




// creating clock
function currentTime() {
    var date = new Date();
    var hour = date.getHours();
    var min = date.getMinutes();
    var sec = date.getSeconds();
    var midday = "AM";
    midday = hour >= 12 ? "PM" : "AM";
    hour = hour == 0 ? 12 : hour > 12 ? hour - 12 : hour;
    hour = updateTime(hour);
    min = updateTime(min);
    sec = updateTime(sec);
    document.getElementById("clock").innerText =
        hour + " : " + min + " : " + sec + " " + midday;

    var months = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December"
    ];

    document.getElementById("clock_date").innerText =
        date.getDate() +
        "-" +
        months[date.getMonth()] +
        "-" +
        date.getFullYear();
    var t = setTimeout(currentTime, 1000); /* setting timer */
}

function updateTime(k) {
    /* appending 0 before time elements if less than 10 */
    if (k < 10) {
        return "0" + k;
    } else {
        return k;
    }
}



function readURL(input, id) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(id).attr("src", e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function mobviewmenu(){
    if($(window ).width() < 992){
        
        $(".main_nav_menu a").not('.sub-item').click(function(e){
            
         //  console.log($(this));
            e.preventDefault();
           
           $(this).parent().children("ul.nav_sub_menu").slideToggle();
        });
    }else{
       // console.log('desktop view')
    }
}



currentTime();
mobviewmenu();

$( window ).resize(function() {
            
    //mobviewmenu();
    
});

$(document).ready(function () {
   

   $("#btn").click(function(e){
    e.preventDefault();

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success ml-3',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
           
            $("#record-form").submit();

        } else if (result.dismiss === Swal.DismissReason.cancel ) {
           // do nothing
           //dismiss delete action box
        }
    })

       
   });

   
    $("#all-checkbox-seleted-otp").click(function(){

        var options = true ;

        if($(this).prop('checked')){
            options = true ;
            $("#btn").show();
        }else{
            options = false ;
            $("#btn").hide();
        }

        $('.multi-select-ids').each(function(){
            $(this).prop("checked",options);
        });
      
    });

    $('.multi-select-ids').click(function(e){
      
        var count = 0;

        $('.multi-select-ids').each(function(){
            
            if( $(this).prop("checked")){
               
                count++;
            }

        });

        if(count > 0){
            $("#btn").show();
        }else{
            $("#btn").hide();
        }
       
    });

    $("#menu_toggle_btn i").click(function () {
        $(".main-header").slideToggle();
    });

          
    $("img").not(".no-light-box").click(function(){

       
        lightBox = $("<div></div>");
        
        lightBox.addClass('imageLightBox');
        

        imgwrapper = $("<div class='imagewrapper'></div>");

        lightBox.append(imgwrapper);
        
        lightBox.append(`<div class="imageLightBoxclose">x</div>`);
        var src= $(this).attr('src');
        imgwrapper.append(`<img src="${src}" />`)

        
        $('body').append(lightBox);
        $('.wrapper').css({
            "filter":"blur(3px)"
        });
        

        
        $('.imageLightBox').not('img').click(function(e){
            
            if(e.target.nodeName == "IMG"){
                return false;
            }
            lightBox.remove();
            $('.wrapper').css({
                "filter":"blur(0px)"
            });
         });

    });

});

function notificationSound() {
    let src = rootPath + "public/sounds/notification-1.ogg";
    let audio = new Audio(src);
    audio.play();
}

function NotificationReadAsMark() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    $("#mark-as-unread").click(function () {
        $.ajax({
            url: rootPath + "notifications-mark-as-read",
            method: "POST",
            success: function (result) {
                if (result == "true") {
                    $("#nofifications_wapper > a").each(function () {
                        $(this).removeClass("unread_notification");
                        $(this).addClass("read_notification");
                    });
                    $(".notification_count").text("");
                }
            }
        });
    });
}

$(".action-btns span").click(function (e) {
    e.preventDefault();
    if ($(this).attr("type") == "accept") {
        alert("Accept booking id:" + $(this).attr("id"));
    }

    if ($(this).attr("type") == "reject") {
        alert("Reject booking id:" + $(this).attr("id"));
    }
});

function record_delete(e) {
    e.preventDefault();
    // console.log(e);
    if(e.target.nodeName == "I"){
        // for tag a > i element
       delete_target = e.target.parentElement.href;
    }else{
        // for a tag element
        delete_target = e.target.href;
    }
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success ml-3',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
           
            window.location = delete_target;

        } else if (result.dismiss === Swal.DismissReason.cancel ) {
           // do nothing
           //dismiss delete action box
        }
    })
}

//driver_preview
function popUPDetail(TargetEle,id_attr){
    $(TargetEle).hover(function(e) {

       data_id = TargetEle+"_" + $(this).attr(id_attr);
        data = $(data_id).html();
        $('.popElement').remove();

        mousePosX = e.originalEvent.x + 20 + "px";
        mousePosY = e.originalEvent.y + 50 + "px";

        //$(this).parent().first().css("position", "relative");
        ele = $("<div></div>");

        ele.addClass('popElement');

        ele.append(data)

        $(this).parent().first().css('position','relative').append(ele);

        // ele.css({
        // "left": -(ele.width()/2)+20,
        // "bottom": ($(this).parent().height())+20 
        // });

         ele.css({
        "left": "50%",
        "bottom": ($(this).parent().height())+5,
        "-webkit-transform": "translateX(-50%)",
        "transform": "translateX(-50%, -50%)"
        });
        
        
        


        }, function() {
        //leaving mousing
        $('.popElement').remove();
        });





     

        // window.addEventListener("resize", myFunction);
        
        // var x = 0;
        //     function myFunction() {
        //     var txt = x += 1;
        //    console.log(txt)

        //     }

}