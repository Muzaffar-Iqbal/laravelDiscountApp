<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ site_title }}</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600"
              rel="stylesheet" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet"
              href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script
        src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script
        src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <style type="text/css">
        @import url('https://fonts.googleapis.com/css?family=Poppins');
            #customCssForm textarea {
                width: 100%;
                max-width: 100%;
            }
            #customCssForm button {
                float: right;
                margin-top: 15px;
            }
            #message {
                display:none;
                color: green;
                margin-top: 10px;
                font-size: 16px;
                font-weight: bold;
            }
            label.enablebtn {
                display:  block;
                padding: 5px 10px;
            }

            label.enablebtn input {
                margin-right:  5px;
                position:  relative;
                top: 2px;
            }
            select.form-control.select-template {
                max-width:  240px;
            }
            input#customCssE {
                position:  relative;
                top: 2px;
                margin-right:  6px;
            }
            cc, label, textarea {
                font-family: 'Poppins', sans-serif;
            }
        </style>

    </head>
    <body>
        <?php
        $value = session('success');
        if ($value != '') {
            ?>
            <div class="alert alert-success"
                 style="padding: 0px; margin: 0px; text-align: center;">
                <strong>Success!</strong> Successfully Completed.
            </div>
            <?php
            session()->forget('success');
        }
        ?>
        <header class="header">
            <div class="container">
                <div class="row">@include('includes.header')</div>
            </div>
        </header>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                <br>
                <h2 class="center" style="text-align: center;font-family: 'Poppins', sans-serif;">Add Snippets to Your Theme</h2>
                <form action="/design" id="customCssForm" method="POST">

<hr>
                <div class="form-group">
                

                </div>
                </form>
                </div>
            </div>
        </div>  

        <script>
        
        $(document).on('submit', '#customCssForm', function (e) {
            e.preventDefault();
            $.ajax({
                url:$(this).attr('action'),
                data:$(this).serialize(),
                beforeSend: function () {
                },
                success: function (data) {
                   $('#message').show();
                   setTimeout(function(){ $('#message').hide(); }, 5000);
                }
            });
        });

        $('#customCssE').change(function(){
            if($(this).is(':checked')){
                $('select[name="customCssEnable"]').val('yes').change();
            }else{
                $('select[name="customCssEnable"]').val('no').change();
            }
        });

        </script>
    </body>
</html>