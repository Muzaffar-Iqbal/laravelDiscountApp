<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ site_title }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }
        </style>
        <script type="text/javascript" src="{!! asset('js/app.js') !!}"></script>
    </head>
    <body>
        <div class="container" style="padding-top: 2%;">
            <form action="{{url()->current()}}/post" method="POST">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">QUANTITY</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" id="inputqty1" placeholder="qty" name="qty[]">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">DISCOUNT(in % like 10,20)</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" id="inputDiscount" placeholder="DISCOUNT" name="discount[]">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">QUANTITY</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" id="inputqty2" placeholder="qty" name="qty[]">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">DISCOUNT(in % like 10,20)</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" id="inputDiscount2" placeholder="DISCOUNT" name="discount[]">
                    </div>
                </div>
                {{ csrf_field() }}
                <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>

    </body>
</html>
