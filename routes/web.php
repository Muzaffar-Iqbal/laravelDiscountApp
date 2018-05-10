<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', function () {
    if (isset($_GET['shop'])) {
        $url = $_GET['shop'];
        return redirect('shopapp?shop=' . $url);
    } else {
        return view('welcome');
    }
});

Route::get('/test', function() {
    echo url('pdf1.pdf');
});

Route::get('/addon', 'AddonController@index');
Route::get('/products', 'Discount@products');
Route::get('/discount', 'Discount@index');
Route::get('/collections', 'Discount@collections');
Route::get('/create-group', 'Discount@groups');
Route::get('/all', 'Discount@allproducts'); //allproducts
Route::get('/save', 'Discount@createmetafields');
Route::post('/{route}/creategroup', 'Discount@creategroup'); //database
Route::post('/all/allstore', 'Discount@allstorediscount');
Route::get('/collfields', 'Discount@collection_mf');
Route::get('/getProdGroup', 'Discount@productGroup');
Route::get('/getCollectsGroup', 'Discount@collectsGroup');
Route::get('/searchProduct', 'Discount@searchProducts');
Route::get('/delete', 'Discount@disableDiscount');
Route::get('/deleteColl', 'Discount@disableCollsDiscount');
Route::post('/products/applybulk', 'Discount@applyInBulk'); //aply bulk products
Route::post('/collections/bulkInCollcts', 'Discount@applyBulkCollcts'); //aply bulk products
Route::get('/edit-group', 'Discount@editGroup');
Route::post('/edit-group/editgroup', 'Discount@editGroups');
Route::get('/deleteGroup', 'Discount@deleteGroups');
Route::get('/removeBulkProducts', 'Discount@removeBulkProducts');
Route::get('/removeBulkColl', 'Discount@removeBulkColl');
Route::get('/addsnippt', 'Discount@AddSnippts');
Route::get('/design', 'Discount@CustomCss');
Route::get('/snippets', 'Discount@SnippetsPage');
// USAMN WORKING

Route::get('/orders', 'Orders@getOrders');
Route::get('/Customers', 'Orders@getCustomers');
Route::get('/CreateOrders', 'Orders@createOrders');
Route::get('/CreateCustomers', 'Orders@createCustomers');

//INQUIRY FORM APP ROUTE DEFINE
Route::get('/inquiryform', 'InquiryForm@index');
Route::get('/emailInovice', 'InquiryForm@sendEmail');
Route::get('/pdf', 'InquiryForm@pdf');

Route::get("/shopapp", function() {
    if (isset($_GET['shop'])) {

        $url = $_GET['shop'];
        $shopUrl = $url;
        $scope = ["write_price_rules","read_price_rules","read_products", "read_orders", "write_products", "read_collection_listings", "read_product_listings", 'write_product_listings', "write_orders", "read_themes", "write_themes", "read_script_tags", "write_script_tags", "read_orders", "write_content"];
        $redirectUrl = url('/install');
        $shopify = Shopify::setShopUrl($shopUrl);

        return redirect()->to($shopify->getAuthorizeUrl($scope, $redirectUrl));
    } else {
        return view('home');
    }
});
Route::get("/install", function(\Illuminate\Http\Request $request) {
    $shopUrl = $_GET['shop'];
    $accessToken = Shopify::setShopUrl($shopUrl)->getAccessToken($request->code);
    $shop = $request->shop;
    $timestamp = $request->timestamp;
    $signature = $request->signature;
    $code = $request->code;
    $hmac = $request->hmac;
    return redirect()->action(
                    'Shopapp@authtoken', ['shop' => $shop, 'hmac' => $hmac, 'timestamp' => $timestamp, 'signature' => $signature, 'code' => $code, 'accessToken' => $accessToken]
    );
});


Route::get('/authtoken', 'Shopapp@authtoken');


