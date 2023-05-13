@php
    use Illuminate\Support\Str;
    use App\Models\VehicleClass;
    use App\Models\Voucher;


    
    $total = 0;
                    
@endphp


<head>
   
<style>

*{
    padding: 0px;
    margin: 0px;
}
.invoice-container{
    max-width: 700px;
    width: 100%;
    margin: 0px auto;
    

}
body{
    font-family: Arial, Helvetica, sans-serif;
    color: #333;
}

strong{
    font-size: 14px;
}
table{
    width: 100%;
    padding: 8px;
}

.invoice-data td{
    padding: 5px;
}
.invoice-data{
    border: 1px solid #eee;
    padding: 0px;
    
}

.invoice-data th{
    background: #eee;
    padding: 8px;
}

.invoice-data td{
    padding: 8px;
    border-bottom: 1px solid #eee;
}

.list{
  list-style: none;
  padding-left: 0px;
}

.company-info, .invoice,
.bill-from, .bill-to,
.booking-data,
.invoice-descp,
.thanks-msg{
    padding: 8px;
}

.title{
    color: #e54e30;
    font-size: 16px;
    margin-bottom: 5px;
}

.booking-detail-data{
    border: 1px solid #eee;
    padding: 3px 0px;
}

.invoice-descp,
.thanks-msg{
    text-align: center;
}

.action-button{
    
    display: grid;
    grid-column-gap: 70%;
    grid-template-columns: auto auto;
    margin-top: 30px; 
    text-align: center;
}
.action-button a{
    padding: 8px;
    text-decoration: none;
    color:#f0f0f0;
    background:#333;
    transition: all 0.3s;

}

.action-button a:hover{
    background:#e54e30 ;

}


@media print{
    .back, .action-button {
        display: none;
    }
}
.btn-back{
    background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  padding: 6px 8px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  transition-duration: 0.4s;
  cursor: pointer;
  background-color: white; 
  color: black; 
  border: 2px solid #333333;

}
.btn-back:hover{
    background-color: #333333;
    color: white;
}

.invoice-status-lable{
    text-align: center;
    font-size: 3em;   
}
</style>

</head>
{{-- <div class="back">
<a href="{{url('bookings')}}" class="btn-back">Go Back</a>
</div> --}}


      <div class="invoice-container">

        <div class="action-button">
            <a href="{{route('generate.pdf',$invoice->id)}}" class="">Save to pdf</a>
            <a href="#" onclick="window.print();" class="">Print</a>
        </div>

                <table id="main-table">
                    <tr>
                        <td class="company-info">
                            <img style="float:left" src="{{ config('app.logo') }}" alt="" width="80"/>
                            <h3 style="margin-top: 30px;">{{ config('app.name') }}</h3>
                        </td>

                        <td class="invoice" style="width:50%">
                                @if ($invoice->status =="paid")
                                    
                                <h1 class="invoice-status-lable" style="color:#218838;">PAID</h1>

                                @else
                                <h1 class="invoice-status-lable" style="color:#C82333">UNPAID</h1>
                                @endif 
                        
                                                        

                            <ul class="list">
                                <li><strong>Invoice Date:</strong>  {{$invoice->created_at}}</li>
                                <li><strong>Invoice #:</strong> {{$invoice->id}}</li>
                            </ul>

                            
                        </td>
                    </tr>

                    <tr>
                        <td class="bill-from">
                            <h5 class="title"> Bill From</h5> 
                            <ul class="list" style="clear: both;">
                                <li>{{Config('app.address')}}</li>
                                <li>{{Config('app.contact')}}</li>
                                <li>{{Config('app.email')}}</li>
                      
                            </ul>
                        </td>
                        <td class="bill-to">
                            <h5 class="title">Bill to</h5> 
                            <ul class="list">
                                <li>{{$booking->first_name}} {{$booking->last_name}}</li>
                                <li>{{$booking->contact_no}}</li>
                                <li>{{$booking->email}}</li>
                            </ul>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" class="booking-data">
                            <h5 class="title">Booking Details</h5>
                            <div class="booking-detail-data">

                                <ul class="list">
                                    <li><strong>Booking ref #: </strong>{{$booking->id}}</li>
                                    <li><strong>Pickup Point: </strong>{{$booking->pickup_point}}</li>
                                    <li><strong>Dropoff/Duration </strong>{{$booking->drop_off}} {{$booking->duration}}</li>
                                </ul>

                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <table class="invoice-data">
                                <tr>
                                    <th>Car Class</th>
                                    {{-- <th>Kilometer/hour</th> --}}
                                    <th></th>
                                    <th style="width:200px">Total</th>
                                </tr>
                
                                <tr>
                                    <td>{{ VehicleClass::getById($booking->v_class) }}</td>
                                    <td></td>
                
                                    {{-- <td>kilometers</td> --}}
                
                                    <td style="text-align:right;">
                                        {{Config('currency-symbol')}} {{$booking->price}}

                                    </td>
                                       
                                </tr>

                                <tr>
                                    <td colspan="3">
                                        <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <br>
                                    </td>
                                </tr>

                                

                                <tr>
                                    <td></td>
                                    <th >Discount</th>
                                    <td style="text-align:right;">
                                        @if (Voucher::isValid($booking->voucher_code))
                                            
                                        {{Config('currency-symbol')}} {{Voucher::discount($booking->voucher_code)}}
                                      

                                        @else
                                        {{Config('currency-symbol')}} 0
                                        @endif
                                        
                                    </td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <th>VAT</th>
                                    @php
                                     $total = $total + Voucher::getnewprice($booking->voucher_code,$booking->price);
                                       $vat = $total * (Config('vat') / 100) ;
                                  
                                        if(Config('vat') > 0){

                                            $total = $total + $vat;
                                           
                                        }
                                    @endphp
                                    <td style="text-align: right;">
                                        {{Config('currency-symbol')}} {{ $vat}}
                                        
                                    </td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <th >Grand Total</th>
                                    <td style="text-align: right;">
                                       
                                        {{Config('currency-symbol')}} {{  $total }}
                                    </td>
                                </tr>

                               
                            </table>
                        </td>
                    </tr>

                   

                    <tr>
                        <td colspan="2">
                            <p class="invoice-descp">If you have any questions regarding this invoice please contact our company phone # or email </p>

                            <h2 class="thanks-msg">Thanks for your Business!</h2>
                        </td>
                    </tr>
                </table>  
                
            </div>
          
          
                       


