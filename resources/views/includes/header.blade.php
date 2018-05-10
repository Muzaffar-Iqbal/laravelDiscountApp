<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" crossorigin="anonymous">
<style>
        @import url('https://fonts.googleapis.com/css?family=Poppins');
    body a, body h4, body h3, body h2, body h1, body th, body tr, body td, body span, body p ,body label, body select, body input, body div { 
     font-family: 'Poppins', sans-serif !important;
     }
</style>
<nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
        <li><a href="{{ url('/')}}/all">Store Wide Discount</a></li>
        <li><a href="{{ url('/')}}/collections">Collection Discounts</a></li>
        <li><a href="{{ url('/')}}/products">Product Discounts</a></li>
        <li><a href="{{ url('/')}}/create-group">Discount Groups</a></li>
        <li><a href="{{ url('/')}}/design">Design</a></li>
        <li class="right-btn"><a href="javascript:;" class="adddiscount">Add Discount Group</a></li>
    </ul>
</nav>

@include('includes.modal')


<script>
    jQuery('.adddiscount').click(function (e) {
        e.preventDefault();
        $("#myModalDiscount").modal();
        return false;
    });

    $(function () {
        setNavigation();
    });

    function setNavigation() {
        var path = window.location.pathname;
        path = path.replace('/shopify/', '');
        path = path.replace(/\/$/, "");
        path = decodeURIComponent(path);

        $(".nav li a").each(function () {
            var href = $(this).attr('href');
            if (href.indexOf(path) >= 0) {
                $(this).closest('li').addClass('active');
            }
        });
    }

</script>
<style type="text/css">
    nav {
        border: 0 none;
        margin: 0 !important;
        padding: 0;
    }
    .detailPanel  .fa {
        font-size: 16px !important;
        padding-left: 15px;
        cursor: pointer;
    }
    .nav.navbar-nav {
        width: 100%;
    }
    .navbar-nav .right-btn {
        float: right !important;
    }
    header {
        background: #222222 none repeat scroll 0 0;
        height: auto;
        margin: 0;
        padding: 0;
    }
    .adddiscount {
        background: #cc0000 none repeat scroll 0 0 !important;
        border-radius: 3px;
        color: #fff !important;
        font-weight: 600;
        opacity: 1;
    }
</style>