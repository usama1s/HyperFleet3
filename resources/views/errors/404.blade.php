<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Page Not Found</title>

    <style>

        h1{
            text-align: center;
            font-size: 8em;
            padding: 0px;
            margin: 0px;
            margin-top: 20%; 
        }
        h3{
            text-align: center;
            padding: 0px;
            margin: 0px;
        }
        a{
            text-align: center;
            display: block;
            text-decoration: none;
            padding: 8px;
            color: #ffffff;
            background: #000;
            width: 200px;
            margin: 10px auto;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        a:hover{
           box-shadow:0px 4px 3px 1px #938c8c
        }
    </style>
</head>
<body>
    <h1>Oops!</h1>
    <h3>404 - PAGE NOT FOUND</h3>
<a href="{{url('/')}}">Go Back</a>
</body>
</html>