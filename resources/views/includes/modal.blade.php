
<style type="text/css">
    .qtyRange {
        display: block;
        font-size: 11px;
        margin: 2px 0 0;
        padding: 0 5px;
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
        padding: 10px;
    }
    .title .col-md-6 {
        display: inline-block;
        float: unset;
        margin: 0 0 0 3%;
        padding: 0;
        text-align: left;
        width: 45%;
    }
    .alert {
        border: 1px solid transparent;
        border-radius: 1px;
        margin-bottom: 9px;
        padding: 5px;
    }
    .saveBtn div {
        text-align: center;
    }
    body {
        padding-bottom: 2%;
    }

    br {
        clear: both;
    }
    .qty-title {
        background: rgba(0, 0, 0, 0.05) none repeat scroll 0 0;
        color: #000;
        padding: 7px;
    }
    .qty-section {
        border: 1px solid #ccc;
        padding: 0;
    }
    .qty-section .form-group {
        padding: 10px;
    }
    .qty-section .form-control {
        display: inline-block;
        max-width: 150px;
        width: 100%;
    }

    .btn {
        background: #286090 none repeat scroll 0 0;
        color: #fff;
        margin-left: 22px;
        padding: 6px 22px;
        width: 86px;
    }
    .form-group.row.saveBtn {
        margin-top: 30px;
        text-align: left;
    }
    .form-group {
        padding: 0;
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
        padding: 11px;
        text-align: left;
        text-transform: uppercase;
        transition: all 0.4s ease 0s;
        width: 100%;
    }

    .modal-header .close {
        margin-top: -2px;
        position: relative;
        z-index: 99999;
    }
    .subacc {
        display: none;
        padding: 10px;
    }
    .subacc span {
        padding: 16px;
    }

</style>

<!-- Modal -->
<div class="modal fade" id="myModalDiscount" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ADD DISCOUNT GROUP</h4>
            </div>
            <div class="modal-body">
                <form action="{{url()->current()}}/creategroup" method="POST">
                    {{ csrf_field() }}
                    <div class="">
                        <h4>STEP:1 Discount Group</h4>
                        <div class="form-group col-md-10 col-lg-10 col-sm-12">
                            <label>Discount Group Title</label> <input type="text" class="form-control" id="gtitle" name="gtitle" placeholder="Group Title" required="">
                        </div>
                        <br>
                        <div class="form-group col-md-12 col-lg-12 col-sm-12">
                            <label>Discount Group Type</label> <select class="form-control"
                                                                       name="gtype">
                                <option value="0">Choose Type</option>
                                <option value="1">Fixed amount off</option>
                                <option value="2">Percentage Off</option>
                            </select>
                        </div>
                        <br>
                        <h4>STEP:2 Configure Price Breaks</h4>
                        <div class="col-md-12 col-lg-12 col-sm-12 qty-section">
                            <div class="qty-title">Minimum Quantity For Discount To Apply</div>
                            <div class="error alert alert-danger" style="display: none;">Enter
                                Value & Quantity!</div>
                            <div class="form-group  ">
                                <input type="text" class="form-control qty" name="qty[]"
                                       placeholder="Quantity" required=""> <input type="text"
                                       class="form-control" name="val[]" placeholder="Value"
                                       required="">
                                <div class="add btn qtybtns">Add</div>
                                <span class="qtyRange"></span>
                            </div>

                        </div>
                        <br>
                        <div class="modal-footer">
                            <div class="form-group row saveBtn">
                                <div class="offset-sm-2 col-sm-12">
                                    <button type="submit" class="btn btn-primary" style="width: 20%; margin: auto;">Create</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">

    $(document).on('click', '.add', function () {
        var $this = $(this);
        $('.error').fadeOut();
        if ($this.prev('input').val() != '' && $this.prev().prev('input').val() != '')
        {
            var html = '<div class="form-group qty-added"><input class="form-control qty" name="qty[]" placeholder="Quantity" required="" type="text">  <input class="form-control " name="val[]" placeholder="Value" required="" type="text"><div class="add btn qtybtns">Add</div><div class="btn clear">Delete</div><span class="qtyRange"></span></div>';
            $(this).parent().parent().append(html);
            $(this).hide();
        } else
        {
            $('.error').fadeIn();
        }

    });
    $(document).on('click', '.clear', function () {
        $('.error').fadeOut();
		$(this).parent().prev().children('.qtybtns').show();
        $(this).parent().remove();
		
        var size = $('.qty-added').length;
        if (size <= 0) {
            $('.add.btn.qtybtns').show();
        }
        UpdateQtyRange();
    });
    $(document).on('change', '.form-control.qty', function () {
        UpdateQtyRange();
    });
    function UpdateQtyRange()
    {
        arr = [];
        $('.form-control.qty').each(function (index) {
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
            $('.form-group:nth-child(' + j + ') .qtyRange').text('Quantity Range For Discount:' + arr[i] + '' + nextVal);
        }
        // $('.qtyRange').text(arr);
    }
    $(document).on('click', '.edit', function () {
        var gid = $(this).attr('data-id');
        window.location.href = "{{ url('/')}}/edit-group?g=" + gid;

    });
</script>
