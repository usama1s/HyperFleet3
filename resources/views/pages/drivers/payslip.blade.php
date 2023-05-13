<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .invoice-container{
    max-width: 700px;
    width: 100%;
    margin: 0px auto;

}
table{
    width: 100%;
    padding: 8px;
}
.list{
  list-style: none;
  margin-left: 286px;
  padding: 7px;
  margin-top: 0px;
  padding-left: 0px;
}
.invoice-data th{
    background: #eee;
    padding: 8px;
}

.invoice-data td{
    padding: 20px;
    border-bottom: 1px solid #eee;
    border-left: 1px solid #eee;
}

.action-button{
    
    display: grid;
    grid-column-gap: 70%;
    grid-template-columns: auto auto;
    margin-top: 30px; 
    text-align: center;
}

body{
    font-family: Arial, Helvetica, sans-serif;
    color: #333;
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

    </style>
</head>

<body>
    <div class="invoice-container">

        <div class="action-button">
            <a href="" class="">Save to pdf</a>
            <a href="#" onclick="window.print();" class="">Print</a>
        </div>

        <table id="main-table">
            <tr>
                <td colspan="6" style="margin-left: 33px;">
                    <img style="margin-top: 20px; margin-left: 302px;" src="{{ config('app.logo') }}" alt="" width="80"/>
                    <h1 style="margin-top: 0px; margin-left: 239px;"><strong>{{ config('app.name') }}</strong></h1>
                </td>
            </tr>
            <tr>

                <td colspan="4" class="bill-from">                
                    <ul class="list">
                        <li>{{Config('app.address')}}</li>
                        <li>{{Config('app.contact')}}</li>
                        <li>{{Config('app.email')}}</li>
              
                    </ul>
                </td>
               
            </tr>

            <tr>
               <td><label>Empolyee Name:</label></td>
               <td><span></span></td>
               <td><label>Pay Dated:</label></td>
               <td> <span></span></td>
            </tr>

            <tr>
                <td><label>ID Number:</label></td>
                <td> <span></span></td>
            </tr>
            <tr>
                <td><label>Bank details:</label></td>
                <td><span></span></td>
            </tr>
            <tr>
                <td><label>Tax Number:</label></td>
                <td><span></span></td>
            </tr>

            <tr>
                <table class="invoice-data">
                    <thead>
                        <tr>
                            <th>Earnings</th>
                            <th>Amount</th>
                           
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                     
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                       
                            <td></td>
                            <td></td>
                        </tr>
                   
                        <tr>
                            
                            <td>Authorized By
                              <hr>
                                </td>
                        </tr>

                    </tbody>
                </table>
            </tr>
        </table>

    </div>
</body>
</html>