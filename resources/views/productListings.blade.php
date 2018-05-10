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
            .selectAll, .removeAll {
                background: #286090 none repeat scroll 0 0;
                border: 1px solid transparent;
                border-radius: 3px;
                color: #fff !important;
                padding: 8px 16px;
            }

            .checkbox input[type="checkbox"], .checkbox-inline input[type="checkbox"],
            .radio input[type="radio"], .radio-inline input[type="radio"] {
                margin-top: 6px;
            }

            .selectGroup {
                margin-bottom: 15px;
                margin-top: 16px;
            }

            #myModalDiscount.modal {
                z-index: 9999999 !important;
                padding-left: 0 !important;
            }

            .detail-panel .form-control {
                float: left;
            }

            .createGroup {
                position: relative;
                top: 6px;
            }

            .right .form-control {
                float: left;
            }

            .right a {
                position: relative;
                top: 6px;
            }

            .bulkGroup .form-control {
                max-width: 100%;
                width: 100%;
            }

            .bulksave {
                clear: both;
                display: inline-block;
                margin: auto;
                text-align: center;
                width: 100%;
            }
            /*code for bulk*/
            .detail-panel {
                margin-bottom: 10px;
            }

            .alert.alert-danger.choosegrperror {
                margin: 0;
                max-width: 100%;
                padding: 3px;
                position: relative;
                top: -14px;
                width: 100%;
            }

            .detail-panel #selectGroup {
                display: inline-block;
                float: left;
                margin-right: 25px;
                padding-top: 5px;
            }

            header {
                background: #222222 none repeat scroll 0 0;
                height: auto;
                margin: 0;
                padding: 0;
            }

            .input-lg.searchbox {
                width: 100% !important;
            }

            .form-control.input-lg.searchbox {
                /* 	max-width: 100%; */
                width: 100%;
            }
            .productListings img {
                left: 45%;
                position: absolute;
                text-align: center;
                top: 44%;
                z-index:999;
            }

            #custom-search-input {
                background-color: #fff;
                border: 1px solid #e4e4e4;
                border-radius: 6px;
                margin-bottom: 20px;
                margin-top: 23px;
                padding: 3px;
            }

            #custom-search-input input {
                border: 0;
                box-shadow: none;
            }

            #custom-search-input button {
                margin: 2px 0 0 0;
                background: none;
                box-shadow: none;
                border: 0;
                color: #666666;
                padding: 0 8px 0 10px;
                border-left: solid 1px #ccc;
            }

            #custom-search-input button:hover {
                border: 0;
                box-shadow: none;
                border-left: solid 1px #ccc;
            }

            #custom-search-input .glyphicon-search {
                font-size: 23px;
            }

            #myDiv {
                background: transparent none repeat scroll 0 0;
                left: 40%;
                position: fixed;
                top: 60%;
                z-index: 99999;
            }

            span.left {
                display: inline-block;
                float: left;
                margin-left: 37px;
                margin-right: 39px;
            }

            .detail {
                cursor: pointer;
            }

            .checkbox {
                border-color: #ccc;
                border-radius: 1px;
                border-style: solid;
                border-width: 1px 0 0;
                display: inline-block;
                line-height: 28px;
                margin-top: 0;
                padding-top: 12px;
                width: 100%;
            }

            .title .col-md-6 {
                display: inline-block;
                float: unset;
                margin: 0 !important;
                padding: 0 important;
                text-align: left;
                width: 49% !important;
                font-size: 17px !important;
            }

            .col-md-3.detailPanel {
                padding-right: 4%;
                text-align: right;
            }

            .custom-container {
                margin-top: 2%;
            }

            label {
                color: #007ace;
                font-size: 13px;
                text-transform: capitalize;
            }

            .form-control {
                background-color: #fff;
                background-image: none;
                border: 1px solid #ccc;
                border-radius: 0;
                box-shadow: none;
                color: #555;
                display: block;
                font-size: 13px;
                height: 31px;
                line-height: 3.429;
                margin-left: 1%;
                margin-top: 2px;
                padding: 4px 11px;
                text-align: left;
                transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s
                    ease-in-out 0s;
                width: 51%;
            }

            .title {
                padding-top: 25px;
            }

            .saveBtn {
                margin-top: 23px;
                text-align: center;
            }

            .saveBtn .btn.btn-primary {
                font-size: 15px;
                max-width: 186px;
                padding: 8px;
                width: 100%;
            }

            .form-group.row.saveBtn {
                width: 100%;
            }

            .form-control {
                border: 1px solid #ccc;
                border-radius: 8px;
                max-width: 215px;
                width: 100%;
            }

            .selectedGrp {
                background: transparent none repeat scroll 0 0;
                text-align: left;
            }

            .topBtn {
                display: inline-block;
                margin: 26px 2px;
                max-width: 210px;
            }

            .productListings {
                border: 1px solid #ccc;
            }

            .applybukdiscountform h2 {
                font-weight: normal;
                margin-bottom: 2%;
                margin-top: 4%;
                opacity: 0.94;
            }

            .productListings .title {
                background: #e6e6e6 none repeat scroll 0 0;
                padding: 22px;
            }

            .SearchResult {
                margin-bottom: 3%;
            }

            .SearchResult .checkbox label, .radio label {
                padding-left: 0;
            }
            .filter-products {
                border-radius: 0;
                height:  35px;
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
                    <div class="SearchResult" style="display: none;">

                        <div class="productListings">
                            <div class="title">
                                <h3 class="col-md-6">Product Title</h3>
                                <h3 class="col-md-6">Discount Group</h3>

                            </div>
                            <div class="checkbox">
                                <div class="col-md-6 form-check">
                                    <label> <input type="hidden" value="" class="searchId"
                                                   name="selectedProd[]"> <a
                                                   href="https://{{ $shop }}/products/"
                                                   class="searchProdTitle" target="_blank"></a>
                                    </label>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="detail">Discount Group</div>
                                    <input type="hidden" name="prodid" class="searchId " value="">
                                </div>
                                <div class="detail-panel" style="display: none;">
                                    <span class="left">Discount Group</span> <span class="right"> <select
                                            class="form-control" id="" name="discountGroup[]">
                                            <option value="0">Choose discount Group</option>
                                            @foreach($groups as $row)
                                            <option value="{{$row->id}}">{{$row->groupTitle}}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="{{url()->current()}}/applybulk" method="POST"
                      class="applybukdiscountform">
                    <h2 class="center" style="text-align: center">Apply Discount On
                        Products</h2>

                    <div class="container custom-container">

                        <div class="topBtn">
                            <a href="javascript:void(0)" class="checkAllProduct">Select All Products</a>
                        </div>
                        <div class="topBtn">
                            <a class="selectAll" href="javascript:void(0)">Add Discount In Bulk</a>
                        </div>
                        <div class="topBtn">
                            <a class="removeAll" href="javascript:void(0)">Remove Discount In Bulk</a>
                        </div>
                        <div class="topBtn">
                           <select class="form-control filter-products">
                                <option value="All">All Products</option>
                                @if ($filter == 'discount')
                                <option value="discount" selected>Discount Product</option>
                                @else 
                                <option value="discount">Discount Product</option>
                                @endif
                           </select>
                        </div>

                        <div class="col-md-3" style="float:right;">
                            <div id="custom-search-input">
                                <div class="input-group col-md-12">
                                    <input type="text" class="form-control input-lg searchbox"
                                           placeholder="Search by title" />
                                </div>
                            </div>
                        </div>
                        <div class="productListings">
                            <div class="title">
                                <h3 class="col-md-6">Product Title</h3>
                                <h3 class="col-md-6">Discount Group</h3>
                            </div>
                            <div titleid="myDiv">
                                <img id="loading-image"
                                     src="{{ url('/') }}/Spinner.gif"
                                     style="display: none;" />
                            </div>


                            @foreach($products as $row)
                            <div class="checkbox">
                                <div class="col-md-6 form-check">
                                    <label> <input type="checkbox" value="{{$row->id}}"
                                                   name="selectedProducts[]" class="productCheckBox"> <input
                                                   type="hidden" value="{{$row->id}}"> <a
                                                   href="https://{{ $shop }}/products/{{$row->handle}}"
                                                   target="_blank">{{$row->title}}</a>
                                    </label>
                                </div>

                                <?php
                                $check = 0;
                                $groupID = '';
                                ?>
                                @foreach ($groupAlreadyAdded as $r)
                                @if ($r->product_id == $row->id)
                                <div class="col-md-3">
                                    @foreach($groups as $rows)
                                    @if($rows->id == $r->discount_grp)
                                    <?php $groupID = $rows->id; ?>
                                    <div class="selectedGrp">{{$rows->groupTitle}}</div>
                                    @endif @endforeach
                                </div>
                                <?php $check = 1; ?>
                                @break;
                                @endif
                                <?php $check = 0; ?>
                                @endforeach
                                @if ( $check == 0)
                                <div class="col-md-3">
                                    <!-- 						<div class="selectedGrp">Add Discount Group</div> -->
                                </div>
                                @endif
                                <div class="col-md-3 detailPanel">

                                    <span class="editGroup" @if($check== 0) style="display: none;"
                                          @endif><i class="fa fa-edit"></i></span> <span
                                        class="deleteGroup" data-prodid="{{$row->id}}"
                                        @if($check== 0) style="display: none;" @endif
                                        data-groupid="{{$groupID}}"><i class="fa fa-trash"></i></span>
                                    <span class="detail"><i class="fa fa-plus" @if($check==
                                                            1) style="display: none;" @endif></i></span> <input
                                        type="hidden" name="prodid" class="prodid"
                                        value="{{$row->id}}">
                                </div>
                            </div>

                            @endforeach
                        </div>
                        {{ csrf_field() }}
                        <!-- 			MODAL FOR APPLU DISCOUNT IN BULK -->
                        <!-- Modal -->
                        <div id="bulkDiscount" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Apply Discount Group In Bulk</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="bulkGroup">
                                                <div class="col-md-4 selectGroup">Choose Discount Group</div>
                                                <div class="col-md-6 selectGroup">
                                                    <select class="form-control" name="selectedDiscGroup">
                                                        <option value="0">Choose discount Group</option>
                                                        @foreach($groups as $row)
                                                        <option value="{{$row->id}}">{{$row->groupTitle}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="bulksave">
                                            <button id="submitInBulk" type="submit"
                                                    class="btn btn-primary" style="width: 30%; margin: auto;">Apply
                                                Discount</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                </form>
                <div class="form-group row saveBtn" style="text-align: center;">
                    <div class="container">
                        <ul class="pagination">
                            @for ($i = 1; $i <= $pages; $i++)
                            @if ($filter == 'discount')
                            <li @if($i == $currentPage) class="active" @endif><a href="{{url()->current()}}?filter_by=discount&page={{$i}}">{{$i}}</a></li>
                            @else
                            <li @if($i == $currentPage) class="active" @endif><a href="{{url()->current()}}?page={{$i}}">{{$i}}</a></li>
                            @endif
                            @endfor
                        </ul>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">CHOOSE DISCOUNT GROUP</h4>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="productid" class="productid" value="">
                                    <div class="alert alert-danger choosegrperror"
                                         style="display: none;">Please Select Discount Group</div>
                                    <div class="alert alert-success succcomp" style="display: none;">Successfuly
                                        Completed</div>
                                    <div class="detail-panel">
                                        <span class="left">Discount Group</span> <span>No Group
                                            Selected</span> <span id="selectGroup">SELECT</span>
                                        <div class="right " style="display: block;">
                                            <select class="form-control" id="" name="discountGroup[]">
                                                <option value="0">Choose discount Group</option>
                                                @foreach($groups as $row)
                                                <option value="{{$row->id}}">{{$row->groupTitle}}</option>
                                                @endforeach
                                            </select> <span><a href="javascript:void(0)">Create Discount Group</a></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div id="submitGroup" class="btn btn-primary"
                                         style="width: 20%; margin: auto;">Save</div>
                                    <div id="disableGroup" class="btn btn-primary"
                                         style="width: 32%; margin: auto; display: none;">Remove
                                        Discount Group</div>
                                    <!--           <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                </div>
                            </div>
                            <div id="groupsDiv" style="display: none;">
                                <span id="selectGroup">SELECT GROUP</span>
                                <div class="right " style="display: block;">
                                    <select class="form-control" id="" name="discountGroup[]">
                                        <option value="0">Choose discount Group</option>
                                        @foreach($groups as $row)
                                        <option value="{{$row->id}}">{{$row->groupTitle}}</option>
                                        @endforeach
                                    </select> <span><a href="javascript:void(0)" class="createGroup">Create Discount Group</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <script type="text/javascript">
jQuery(document).ready(function () {

    jQuery(document).on('click', '#submitInBulk', function (e) {
        e.preventDefault();
        var dopt = 1;
        var opt = $('.bulkGroup select.form-control option:selected').val();
        if (opt == 0)
        {
            dopt = 0;
        }
        var cbox = 0;
        jQuery('.productCheckBox').each(function () {
            jQuery(this).attr('checked', false);
            var ckbox = $(this);
            if (ckbox.is(':checked'))
            {
                cbox = 1;
            }
        });

        if (cbox == 1 && dopt == 1)
        {
            $(".applybukdiscountform").submit();
        } else
        {
            $('.bulkerror').remove();
            $('#bulkDiscount .modal-body').append('<div class="alert alert-danger bulkerror">Please select items to add a discount in bulk.</div>');
        }
    });

    jQuery(document).on('click', '.createGroup', function (e) {
        e.preventDefault();
        jQuery('#myModalDiscount').modal('show');
    });

    jQuery(document).on('click', '.selectAll', function (e) {
        e.preventDefault();
        var atLeastOneIsChecked = $('.productCheckBox:checkbox:checked').length > 0;
        if (atLeastOneIsChecked) {
            jQuery('#bulkDiscount').modal('show');
        }

    });

    jQuery(document).on('click', '.removeAll', function (e) {
        e.preventDefault();
        var prodids = [];
        $('.productCheckBox').each(function () {
            if ($(this).is(':checked')) {
                var id = $(this).val();
                prodids.push(id);
            }
        });
        $.ajax({
            type: "GET",
            data: {data: prodids},
            url: "{{ url('/') }}/removeBulkProducts",
            success: function (msg) {
                location.reload();
            }
        });
    });

    jQuery(document).on('click', '.checkAllProduct', function (e) {
        e.preventDefault();
        if ($(this).hasClass('checked'))
        {
            $(this).removeClass('checked');
            jQuery('.productCheckBox').each(function () {
                jQuery(this).attr('checked', false);
            });
        } else
        {
            $(this).addClass('checked');
            jQuery('.productCheckBox').each(function () {
                jQuery(this).attr('checked', true);
            });
        }


    });

//	new edit
    jQuery(document).on('click', '.editGroup', function () {
        $(this).parent().find('.detail').click();
    });

    jQuery('.detail').click(function () {
        var gid = 0;
        $("#loading-image").show();
        $('.detailPanel').removeClass('activePanel');
        $(this).parent().addClass('activePanel');
        var product_id = $(this).next('input').val();
        $('.productid').val(product_id);
        $.ajax({url: "{{ url('/') }}/getProdGroup?pid=" + product_id, success: function (data) {
                gid = JSON.parse(data);
            }});
        setTimeout(function () {
            $("#loading-image").hide();
            if (gid != 0) {
                jQuery('#myModal .detail-panel').empty();
                jQuery('#disableGroup').show();
                jQuery('#myModal .detail-panel').append(gid);
            } else
            {
                var htmldiv = $('#groupsDiv').html();
                jQuery('#disableGroup').hide();
                jQuery('#myModal .detail-panel').empty();
                jQuery('#myModal .detail-panel').append(htmldiv);
            }
            $("#myModal").modal();
        }, 2000);
    });
// search call
    $('.searchButton').click(function () {
        if ($('.searchbox').val().trim() != '') {
            var title = $('.searchbox').val();
            var title = title.replace('&', "%");
            searchProduct(title);
        }
    });
    $('#submitGroup').click(function (e) {
        e.preventDefault();
        var titl = $('.productid').val();
        var grp = jQuery("#myModal .detail-panel select.form-control option:selected").val();
        var groupTXT = jQuery("#myModal .detail-panel select.form-control option:selected").text();
        if (grp == 0) {
            $('.choosegrperror').show();
            return false;
        }
        $.ajax({
            url: "{{ url('/') }}/save?selectedProd=" + titl + '&discountGroup=' + grp,
            beforeSend: function () {
            },
            success: function (data) {
                if (data)
                {
                    $('.succcomp').show();
                    $('.activePanel').prev().html('<div class="selectedGrp">' + groupTXT + '</div>');
                    $('.activePanel').append('<span class="editGroup"><i class="fa fa-edit"></i></span><span class="deleteGroup" data-prodid="' + titl + '" data-groupid="' + grp + '"><i class="fa fa-trash"></i></span>');
                    //window.location.href = '{{ url("/") }}/products';
                    $('.activePanel .fa-plus').hide();
                }
            }
        });
    });
//	new added
    $(document).on('click', '.deleteGroup', function (e) {
        e.preventDefault();
        var titl = $(this).attr('data-prodid');
        var grp = $(this).attr('data-groupid');
        ;
        $.ajax({
            url: "{{ url('/') }}/delete?selectedProd=" + titl + '&discountGroup=' + grp,
            beforeSend: function () {
            },
            success: function (data) {
                if (data)
                {
                    window.location.href = '{{ url("/") }}/products';
                }
            }
        });
    });

    $('#disableGroup').click(function (e) {
        e.preventDefault();
        var titl = $('.productid').val();
        var grp = jQuery("#myModal .detail-panel select.form-control option:selected").val();
        $.ajax({
            url: "{{ url('/') }}/delete?selectedProd=" + titl + '&discountGroup=' + grp,
            beforeSend: function () {
            },
            success: function (data) {
                if (data)
                {
                    window.location.href = '{{ url("/") }}/products';
                }
            }
        });
    });
    function searchProduct(title)
    {
        $.ajax({
            url: "{{ url('/') }}/searchProduct?title=" + title,
            beforeSend: function () {
            },
            success: function (data) {
                var res = JSON.parse(data);
                $('.searchId').val(res['products'][0]['id']);
                $('.searchProdTitle').text(res['products'][0]['title']);
                $('.SearchResult').show();
            }
        });
    }
});
            </script>

            <script>
                $(document).ready(function () {
                    $("#custom-search-input input").on("keyup", function () {
                        var value = $(this).val().toLowerCase();
                        $(".checkbox .form-check a").filter(function () {
                            var res = $(this).text().toLowerCase().indexOf(value) > -1;
                            if (res) {
                                $(this).parent().parent().parent().show();
                            } else {
                                $(this).parent().parent().parent().hide();
                            }
                        });
                    });

                    $('.filter-products').change(function(){
                    var filter_val = $(this).val().toLowerCase();
                    window.location.href="/products?filter_by="+filter_val;
                    });

                    // alert($('.productListings > .checkbox').length);
                });
            </script>

    </body>
</html>