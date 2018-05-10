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
            .design-template2>table>tbody>tr:nth-child(1) {
    background-image: -moz-linear-gradient( 90deg, rgb(227,227,227) 0%, rgb(244,244,244) 100%);
    background-image: -webkit-linear-gradient( 90deg, rgb(227,227,227) 0%, rgb(244,244,244) 100%);
    background-image: -ms-linear-gradient( 90deg, rgb(227,227,227) 0%, rgb(244,244,244) 100%);
    text-align: center;
    color: black;
}
.design-table td, .design-table th, .design-table tr {
    text-align: center;
    border: 1px solid #B3B3B3;
    padding: 18px;
}
.design-table td {
    background: #F5F5F5;
    width: 50%;
}
.design-template1>table>tbody>tr:nth-child(1) {
    background: #6f524c;
    text-align: center;
    color: white;
}
.design-template3>table>tbody>tr:nth-child(1) {
    background: #182E49;
    color: white;
}
.design-no>table>tbody>tr:nth-child(1) {
    background: black;
    color: white;
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
                <h2 class="center" style="text-align: center;font-family: 'Poppins', sans-serif;">Discount Table Design</h2>
                <form action="/design" id="customCssForm" method="POST">
                <div class="form-group">
                    <label> Select Product table Template </label>
                            <select class="form-control select-template" name="template">
                            <option value="no" @if ($template == 'no') selected @endif >Select Template</option>
                                <option value="template1" @if ($template == 'template1') selected @endif >Template 1</option>
                                <option value="template2" @if ($template == 'template2') selected @endif >Template 2</option>
                                <option value="template3" @if ($template == 'template3') selected @endif >Template 3</option>
                           </select>
                    </div>
<hr>
                <div class="form-group">
                    <label for="comment"><cc><input type="checkbox" id="customCssE" @if ($cssenable == 'yes') checked @endif>Custom CSS  <span style="display:none;">(Enable: <select name="customCssEnable">
                    <option value="yes" @if ($cssenable == 'yes') selected @endif >Yes</option>
                    <option value="no" @if ($cssenable == 'no') selected @endif >No</option>
                    </select>)
                    </span>
                    </label>

                    <textarea class="form-control" rows="10" id="css" name="customCss">{{$css}}</textarea>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <div id="message">Data Saved Successfully !</div>
                </div>
                </form>
                </div>
            </div>

            <div class="preview-table-templates">
            <div class="design-{{$template}} design-table" id="changeTable">
            <h4>Preview:</h4>
    <table style="width:50%;margin:10px auto 10px;">
<tbody>
<tr>
    <th>QTY</th>
    <th>DISCOUNT</th>   
</tr>
<tr>
  <td>Buy 5 Items </td>
  <td>Get 10 $  discount</td> 
</tr>
<tr>
  <td>Buy 10 Items </td>
  <td>Get 15 $  discount</td> 
</tr>
<tr>
  <td>Buy 15 Items </td>
  <td>Get 20 $  discount</td> 
</tr>
    </tbody>
    </table>
  </div>
</div>
        </div>  

        <script>
        // $('.select-template').val({{$template}}).change();
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

        $('.select-template').change(function(){
            var $val = $(this).val();
            $('#changeTable').removeAttr('class');
            $('#changeTable').addClass('design-table').addClass('design-'+$val);
        });
        </script>
    </body>
</html>