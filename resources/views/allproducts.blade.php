<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ site_title }}</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <style type="text/css">
            html {
                font-family: Lato, 'Helvetica Neue', Arial, Helvetica, sans-serif;
                font-size: 14px;
            }

            .table {
                border: none;
            }
            .custom-control-input {
                opacity: 0;
            }
            h2 {
                margin: 3%;
                text-align: center;
            }

            .table-definition thead th:first-child {
                pointer-events: none;
                background: white;
                border: none;
            }

            .table td {
                vertical-align: middle;
            }

            .page-item > * {
                border: none;
            }

            .custom-checkbox {
                min-height: 1rem;
                padding-left: 0;
                margin-right: 0;
                cursor: pointer;
            }
            .custom-checkbox .custom-control-indicator {
                content: "";
                display: inline-block;
                position: relative;
                width: 30px;
                height: 10px;
                background-color: #818181;
                border-radius: 15px;
                margin-right: 10px;
                -webkit-transition: background .3s ease;
                transition: background .3s ease;
                vertical-align: middle;
                margin: 0 16px;
                box-shadow: none;
            }
            .custom-checkbox .custom-control-indicator:after {
                content: "";
                position: absolute;
                display: inline-block;
                width: 18px;
                height: 18px;
                background-color: #f1f1f1;
                border-radius: 21px;
                box-shadow: 0 1px 3px 1px rgba(0, 0, 0, 0.4);
                left: -2px;
                top: -4px;
                -webkit-transition: left .3s ease, background .3s ease, box-shadow .1s ease;
                transition: left .3s ease, background .3s ease, box-shadow .1s ease;
            }
            .custom-checkbox .custom-control-input:checked ~ .custom-control-indicator {
                background-color: #84c7c1;
                background-image: none;
                box-shadow: none !important;
            }
            .custom-checkbox .custom-control-input:checked ~ .custom-control-indicator:after {
                background-color: #84c7c1;
                left: 15px;
            }
            .custom-checkbox .custom-control-input:focus ~ .custom-control-indicator {
                box-shadow: none !important;
            }
            td {
                border: medium none !important;
            }
            .card-block.p-0 {
                float: unset;
                margin: auto;
            }
            .message {
                color:  green;
                font-weight:  bold;
                letter-spacing: 1px;
            }
        </style>
    </head>
    <body>


        <header class="header">
            <div class="container">
                <div class="row">
                    @include('includes.header')
                </div>
            </div>
        </header>

        <main class="container pt-5">
            <div class="card mb-5">
                <h2>Discount on all Products</h2>
                <form action="{{url()->current()}}/allstore" method="POST" class="allProductsSubmit">
                    {{ csrf_field() }}

                    <div class="card-block p-0 col-md-6 col-sm-12">
                        <table class="table   table-sm m-0">
                            <thead class="">
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Enable</td>
                                    <td style="text-align:right;">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="enableAll" @if($enable == 1) checked @endif >
                                                   <span class="custom-control-indicator"></span>
                                        </label>
                                    </td>

                                </tr>
                                <tr class="discount-form" @if($enable != 1) style="display:none;" @endif>
                                    <td>Discount Group</td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" name="discountGroup">
                                                <option value="0">Choose discount Group</option>
                                                @foreach($groups as $row2)
                                                <option value="{{$row2->id}}" @if($enable == 1) selected @endif >{{$row2->groupTitle}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="col-md-12 btn-save" style="text-align:center;"><button type="submit"   class="btn btn-primary">Save</button></div>
                        <div class="message" style="display:none;">Settings Saved Successfully!!</div>
                    </div>
                </form>
            </div>

        </main>
<script>
// $('body').on('change','select[name="discountGroup"], .custom-checkbox input', function(){
// $('.allProductsSubmit').submit();
// });

$('.custom-control-input').change(function() {
        if($(this).is(":checked")) {
            $('.discount-form').show();
            $('.btn-save').show();
        }else{
			$('.discount-form').hide();
		}
    });
$('.allProductsSubmit').submit(function(e){
    e.preventDefault();
    console.log($(this).serialize());
    $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function (data) {
                $('.message').fadeIn();
            setTimeout(function(){
                $('.message').fadeOut();
            },2000);
            }
        });
});
</script>
    </body>
</html>
