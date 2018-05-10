<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ site_title }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

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

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
                width: 100%;
            }


            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
            .form_input{
                border: 1px solid #ccc;
                padding: 10px;
                width: 40%;
            }
            .btn_submit{
                background: #333;
                color:#fff;
                padding: 10px;
                border: 1px solid #ccc;
                cursor: pointer;
            }
            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
        <script type="text/javascript" src="{!! asset('js/app.js') !!}"></script>
    </head>
    <body>
        <?php
        $path = resource_path('custom/test.txt');
        ?>

        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    <form action="/shopapp" method="get">
                        <input class="form_input" type="text" name='shop' required="required" placeholder="Enter Your Store URL ( Without https:// )" >
                        <input type="submit" class="btn_submit" value="SUBMIT">
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
