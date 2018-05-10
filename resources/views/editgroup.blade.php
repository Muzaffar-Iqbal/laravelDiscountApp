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

    </head>
    <body class="">
        <header class="header">
            <div class="container">
                <div class="row">
                    @include('includes.header')
                </div>
            </div>
        </header>

        <?php
        $gtitle = '';
        $gtype = '';
        ?>
        @foreach($data as $k=>$row)
        <?php
        $gtitle = $row->groupTitle;
        $gtype = $row->grouType;
        $gid = $row->gid;
        ?>
        @endforeach
        <form action="{{url()->current()}}/editgroup" method="POST" class="editdiscountPage">
            {{ csrf_field() }}
            <input type="hidden" value="{{$gid}}" name="groupdid" >
            <h2 class="center" style="text-align: center">Edit Discount Group</h2>
            <div class="container custom-container">
                <h4>STEP:1 Discount Group</h4>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                    <label>Discount Group Title</label>
                    <input type="text" class="form-control" id="gtitle"  value="{{$gtitle}}" name="gtitle" placeholder="Group Title" required="">
                </div>
                <br>
                <div class="form-group col-md-6 col-lg-6 col-sm-12">
                    <label>Discount Group Type</label>
                    <select class="form-control"   name="gtype">
                        <option value="0">Choose Type</option>
                        <option value="1" <?php if ($gtype == 1) echo 'selected=""'; ?>>Fixed amount off</option>
                        <option value="2" <?php if ($gtype == 2) echo 'selected=""'; ?>>Percentage Off</option>
                    </select>
                </div>
                <br>
                <h4>STEP:2 Configure Price Breaks</h4>
                <div class="col-md-6 col-lg-6 col-sm-12 qty-section">
                    <div class="qty-title">Minimum Quantity For Discount To Apply</div>
                    <div class="error alert alert-danger" style="display: none;">Enter Value & Quantity!</div>
                    <?php
                    $i = 0;
                    $len = count($data);
                    ?>
                    @foreach($data as $k=>$row)

                    <div class="form-group  ">
                        <input type="text" class="form-control qty" name="qty[]" value="{{$row->qty}}" placeholder="Quantity" required="">
                        <input type="text" class="form-control"   name="val[]"  value="{{$row->value}}" placeholder="Value" required="">
                        @if($i == $len -1)
                        <div class="addd btn qtybtns">add</div>
                        <div class="btn clear">Delete</div>
                        <span class="qtyRange"> <?php echo 'Quantity Range For Discount: ' . $data[$k]->qty . '+'; ?></span>
                        @else
                        <div class="addd btn qtybtns" style="display: none;">add</div>
                        <span class="qtyRange">
                            <?php
                            $next = $data[$k + 1]->qty - 1;
                            echo 'Quantity Range For Discount: ' . $data[$k]->qty . '-' . $next;
                            ?>
                        </span>
                        @endif
                    </div>
                    <?php
                    $i++
                    ?>
                    @endforeach

                </div>
                <br>
                <div class="form-group row saveBtn"  >
                    <div class="offset-sm-2 col-sm-12">
                        <button type="submit" class="btn btn-primary" style="width: 20%;margin: auto;">Update</button>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
$(document).on('click', '.editdiscountPage .addd', function () {
    var $this = $(this);
    $('.error').fadeOut();
    if ($this.prev('input').val() != '' && $this.prev().prev('input').val() != '')
    {
        var html = '<div class="form-group qty-addded"><input class="form-control qty" name="qty[]" placeholder="Quantity" required="" type="text">  <input class="form-control " name="val[]" placeholder="Value" required="" type="text"><div class="addd btn qtybtns">add</div><div class="btn clear">Delete</div><span class="qtyRange"></span></div>';
        $(this).parent().parent().append(html);
        $(this).hide();
    } else
    {
        $('.error').fadeIn();
    }

});
$(document).on('click', '.editdiscountPage .clear', function () {
    $('.error').fadeOut();
    $(this).parent().remove();
    var size = $('.editdiscountPage .qty-addded').length;
    if (size <= 0) {
        $('.editdiscountPage .addd.btn.qtybtns').show();
    }
    UpdateQtyRange();
});
$(document).on('change', '.editdiscountPage .form-control.qty', function () {
    UpdateQtyRange();
});
function UpdateQtyRange()
{
    arr = [];
    $('.editdiscountPage .form-control.qty').each(function (index) {
        arr.push($(this).val());
    });

    for (var i = 0; i < arr.length; i++)
    {
        var j = i + 3;
        var nextVal = arr[i + 1];
        nextVal = nextVal - 1;
        if (isNaN(nextVal)) {
            nextVal = '+';
        } else {
            nextVal = '-' + nextVal;
        }
        $('.editdiscountPage .form-group:nth-child(' + j + ') .qtyRange').text('Quantity Range For Discount:' + arr[i] + '' + nextVal);
    }
    // $('.qtyRange').text(arr);
}
$(document).on('click', '.editdiscountPage .edit', function () {
    var gid = $(this).attr('data-id');
    window.location.href = "{{ url('/') }}/edit-group?g=" + gid;

});
            </script>

            <style type="text/css">
                .qtyRange {
                    display: block;
                    font-size: 11px;
                    margin: 2px 0 0;
                    paddding: 0 5px;
                }
                .accordDiv {
                    position: relative;
                }
                .buttons.actions {
                    position: absolute;
                    right: 18px;
                    top: 5px;
                    z-index: 9999;
                }
                .qty-section .form-group {
                    margin: 0;
                }
                .qty-section .form-group {
                    paddding: 10px;
                }
                .title .col-md-6 {
                    display: inline-block;
                    float: unset;
                    margin: 0 0 0 3%;
                    paddding: 0;
                    text-align: left;
                    width: 45%;
                }
                .alert {
                    border: 1px solid transparent;
                    border-radius: 1px;
                    margin-bottom: 9px;
                    paddding: 5px;
                }
                body {
                    paddding-bottom: 2%;
                }
                .custom-container {
                    border: 1px solid #ccc;
                    box-shadow: 0 1px 2px #ccc;
                    margin-top: 4%;
                    paddding: 20px;
                }
                br {
                    clear: both;
                }
                .qty-title {
                    background: rgba(0, 0, 0, 0.05) none repeat scroll 0 0;
                    color: #000;
                    paddding: 7px;
                }
                .qty-section {
                    border: 1px solid #ccc;
                    paddding: 0;
                }
                .qty-section .form-group {
                    paddding: 10px;
                }
                .qty-section .form-control {
                    display: inline-block;
                    max-width: 150px;
                    width: 100%;
                }
                /*.btn.qtybtns {
                  background: #286090 none repeat scroll 0 0;
                  color: #fff;
                  margin-left: 22px;
                  paddding: 6px 22px;
                  width: 86px;
                }*/
                .editdiscountPage .btn {
                    background: #286090 none repeat scroll 0 0;
                    color: #fff;
                    margin-left: 22px;
                    paddding: 6px 22px;
                    width: 86px;
                }
                .editdiscountPage .form-group.row.saveBtn {
                    margin-top: 30px;
                    text-align: left;
                }
                .form-group {
                    paddding: 0;
                }
                h4 {
                    font-size: 14px;
                    font-weight: 600;
                    opacity: 0.82;
                }
                .accordion {
                    background-color: #eee;
                    border: medium none;
                    color: #444;
                    cursor: pointer;
                    font-size: 13px;
                    font-weight: normal;
                    margin: 1px;
                    outline: medium none;
                    paddding: 11px;
                    text-align: left;
                    text-transform: uppercase;
                    transition: all 0.4s ease 0s;
                    width: 100%;
                }

                .editdiscountPage .saveBtn div {
                    text-align: left !important;
                }
                .subacc {
                    display: none;
                    paddding: 10px;
                }
                .subacc span {
                    paddding: 16px;
                }
                .panel {
                    /*    paddding: 0 18px;
                        background-color: white;
                        max-height: 0;
                        overflow: hidden;
                        transition: max-height 0.2s ease-out;*/
                }
            </style>
    </body>
</html>
