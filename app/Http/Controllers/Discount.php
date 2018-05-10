<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RocketCode\Shopify\API;
use Session;

header("Access-Control-Allow-Origin: * ");
class Discount extends Controller
{
    public function allstorediscount(Request $request)
    {
        $session = session()->all();
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);

        $enableall = $request->enableAll;
        $groupID = $request->discountGroup;
        $res = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/metafields.json'
        ]);

        $mtf = $res->metafields;
        $deletemtfid = '';
        foreach ($mtf as $key => $value) {
            // code...
            if ($value->namespace == 'discountOnAll') {
                // code...
                $deletemtfid = $value->id;
                $shopify->call([
                    'METHOD' => 'DELETE',
                    'URL' => '/admin/metafields/' . $deletemtfid . '.json'
                ]);
            }
        }
        if ($enableall == 'on') {
            $result = $shopify->call([
                'METHOD' => 'POST',
                'URL' => '/admin/metafields.json',
                'DATA' => [
                    'metafield' => [
                        "namespace" => "discountOnAll",
                        "key" => 'groupid',
                        "value" => $groupID,
                        "value_type" => "integer"
                    ]
                ]
            ]);
            //session()->regenerate();
            session([
                'success' => 'Successfully Completed!'
            ]);
            return redirect()->action('Discount@allproducts');
        } else { // delete store all meta fields
            return redirect()->action('Discount@allproducts');
        }
    }

    public function allproducts()
    {

        $session = session()->all();
        if (empty($session['shopurl'])) {
            return redirect('/');
        }
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);

        $res = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/metafields.json'
        ]);
        $on = 0;
        $groupid = '';
        $mtf = $res->metafields;
        foreach ($mtf as $key => $value) {
            if ($value->namespace == 'discountOnAll') {
                $on = 1;
                $groupid = $value->value;
            }
        }

        if ($groupid != '') {
            $groups = DB::table('groups')->get()->where('id', $groupid);
        } else {
            $groups = DB::table('groups')->get()->where('shop', $session['shopId']);
        }

        $arr = $groups->toArray();
        $data = array(
            'enable' => $on,
            'groups' => $arr
        );

        return view('allproducts')->with($data);
    }

    // code for generate discount code
	public function index(Request $request) {
        $couponcode = $request->code;
        $shop = $request->shopp;
		$disPrice = $request->dp;
		$prodid = $request->prodid;
		$lastcode = $request->lastcode;
		$lastPriceRule = $request->lastPriceRule;
		$lastcodeid = $request->lastcodeid;
		$data = explode(",", $prodid);
		$randomnumber = mt_rand(0, 100);
        $finalCoupon = $couponcode . '' . $randomnumber;
        $session = session()->all();
        $accesstoken = '';

        if (DB::table('shop')->where('shop_name', '=', $shop)->exists()) {
            $shopdata = DB::table('shop')->where('shop_name', '=', $shop)->first();
            $accesstoken = $shopdata->access_token;
        }

        $shopify = App::make('ShopifyAPI', [
            'API_KEY' => API_KEY,
            'API_SECRET' => API_SECRET,
            'SHOP_DOMAIN' => $shop,
            'ACCESS_TOKEN' => $accesstoken
        ]);

		
		if ($lastcodeid != 'null' && $lastPriceRule != 'null' && $lastcode != 'null' ) {
			$result = $shopify->call([
				'METHOD' => 'DELETE',
				'URL' => '/admin/price_rules/' . $lastPriceRule . '/discount_codes/' . $lastcodeid . '.json',
			]);
        }
        
		// CREATE A PRICE RULE FOR DISCOUNT
		$result = $shopify->call([
			'METHOD' => 'POST',
			'URL' => '/admin/price_rules.json',
			'DATA' => [
				'price_rule' => [
					"title" => "discountappcode",
					"target_type" => "line_item",
					"target_selection" => "entitled",
					"allocation_method" => "across",
					"value_type" => "fixed_amount",
					"value" => "-" . $disPrice,
					"customer_selection" => "all",
					"entitled_product_ids" => $data,
					"starts_at" => "2017-01-19T17:59:10Z",
				],
			],
		]);
		$price_rule_id = $result->price_rule->id;
		$result = $shopify->call([
			'METHOD' => 'POST',
			'URL' => 'price_rules/' . $price_rule_id . '/discount_codes.json',
			'DATA' => [
				'discount_code' => [
					"id" => $price_rule_id,
					"code" => $finalCoupon,
				],
			],
		]);
		$couponcode_id = $result->discount_code->id;
		$couponcode = $result->discount_code->code;
		$lastPriceRule = $price_rule_id;
		$data = array(
			'couponcode' => $couponcode,
			'lastPriceRule' => $lastPriceRule,
			'lastcodeid' => $couponcode_id,
		);
		$myJSON = json_encode($data);
		echo $myJSON;
    }
    

    public function products(Request $request)
    {
        $session = session()->all();
        if (empty($session['shopurl'])) {
            return redirect('/');
        }

        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);
        
        $groups = DB::table('groups')->get()->where('shop', $session['shopId']);
        // $dd = DB::table('shop')->select('*')->get();
        // print_r($dd);
        // $dd = DB::table('shop')->delete();
        $arr = $groups->toArray();
        $productGroup = DB::table('products')->orderBy('id', 'desc')
                ->select('*')
                ->get();
        $groupAdded = $productGroup->toArray();
        // get page no
        $pageNumber = 1;
        if ($request->has('page')) {
            $pageNumber = $request->input('page');
        }

        $filter = 'all';
        if (isset($_GET['filter_by'])) {
            $filter = $_GET['filter_by'];
        }

        if($filter == 'discount' ){
            $all_ids = array();
           foreach($groupAdded as $product){
            array_push($all_ids,$product->product_id);  
           }

           $all_ids = array_unique($all_ids);

           //count discount products 
           $count_all = ceil(count($all_ids));
           $pages = ceil(count($all_ids) / 50);
           $start = ($pageNumber-1)*50;
           $end = $pageNumber*50;

           $sorted_ids = array_slice($all_ids, $start, $end);
           $product_ids = join(",",$sorted_ids);

           $result = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/products.json?ids='.$product_ids
            ]);

            $products = $result->products;
        }else{
        // for product fetch defaul first page
        
        $countProducts = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/products/count.json'
        ]);
        $pages = ceil($countProducts->count / 50);

        $result = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/products.json?page=' . $pageNumber
        ]);

        $products = $result->products;
        }
        
        $data = array(
            'products' => $products,
            'pages' => $pages,
            'shop' => $session['shopurl'],
            'groups' => $arr,
            'groupAlreadyAdded' => $groupAdded,
            'filter' => $filter,
            'currentPage' => $pageNumber
        );
        return view('productListings')->with($data);
    }

    public function createmetafields(Request $request)
    {
        $session = session()->all();
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);
        $products = $request->input('selectedProd'); // get prod ids and discount group
        $groups = $request->input('discountGroup');
        $result = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/products/' . $products . '/metafields.json'
        ]);
        $cmt = $result->metafields;
        foreach ($cmt as $key => $value) {
            if ($value->namespace == 'inventory') {
                $result = $shopify->call([
                    'METHOD' => 'DELETE',
                    'URL' => '/admin/metafields/' . $value->id . '.json'
                ]);
            }
        }

        $discount = DB::table('discount')->get()->where('gid', $groups);
        $type = DB::table('groups')->get()->where('id', $groups);
        $arr = $type->toArray();

        foreach ($arr as $key => $row) {
            // code...
            $gtype = $row->grouType;
            $gtitle = $row->groupTitle;
            $gid = $row->id;
        }
        // store prod ids in db
        DB::table('products')->insert([
            'product_id' => $products,
			'shop' => $session['shopId'],
            'discount_grp' => $gid
        ]);
        foreach ($discount as $user) {
            $val = $user->value;
            $qty = $user->qty;
            $gid = $user->gid;
            $result = $shopify->call([
                'METHOD' => 'POST',
                'URL' => '/admin/products/' . $products . '/metafields.json', // modify
                'DATA' => [
                    'metafield' => [
                        "namespace" => "inventory",
                        "key" => "discount",
                        "value" => $gid,
                        "value_type" => "integer"
                    ]
                ]
            ]);
        }
        //session()->regenerate();
        session([
            'success' => 'Successfully Completed!'
        ]);
        echo "true";
    }

    public function removeBulkProducts(Request $req)
    {
        $products = $req->input('data');
        $session = session()->all();
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);
        foreach ($products as $key => $value) {
            DB::table('products')->where('product_id', '=', $value)->delete(); // delete the prodID from DB
            $result = $shopify->call([
                'METHOD' => 'GET',
                'URL' => '/admin/products/' . $value . '/metafields.json'
            ]);
            $cmt = $result->metafields;
            foreach ($cmt as $key => $value) {
                if ($value->namespace == 'inventory') {
                    $result = $shopify->call([
                        'METHOD' => 'DELETE',
                        'URL' => '/admin/metafields/' . $value->id . '.json'
                    ]);
                }
            }
        }
    }

    public function removeBulkColl(Request $req)
    {
        $products = $req->input('data');
        $session = session()->all();
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);
        foreach ($products as $key => $value) {
            DB::table('collection')->where('collection_id', '=', $value)->delete(); // delete the prodID from DB
            $result = $shopify->call([
                'METHOD' => 'GET',
                'URL' => '/admin/collections/' . $value . '/metafields.json'
            ]);
            $cmt = $result->metafields;
            foreach ($cmt as $key => $value) {
                if ($value->namespace == 'inventory') {
                    $result = $shopify->call([
                        'METHOD' => 'DELETE',
                        'URL' => '/admin/metafields/' . $value->id . '.json'
                    ]);
                }
            }
        }
    }

    public function creategroup($route, Request $request) // modify
    {
        $grouptitle = $request->gtitle;
        $gtype = $request->gtype;

        $session = session()->all();
        $shopUrl = $session['shopurl'];
        if ($shopUrl != "") {
            $shop = $shopUrl;
        } else {
            $shop = 'brown-dev.myshopify.com';
        }
        $qty = $request->qty;
        $val = $request->val;
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);

        $id = DB::table('groups')->insertGetId([
            'groupTitle' => $grouptitle,
            'grouType' => $gtype,
            'shop' => $session['shopId']
        ]);
        $arrQty = [];
        foreach ($qty as $key => $quantity) {
            $value = $val[$key];
            $qty = $quantity;
            $qv = $value . '-' . $qty;
            array_push($arrQty, $qv);
            $res = DB::table('discount')->insert([
                [
                    'gid' => $id,
                    'qty' => $qty,
                    'value' => $value
                ]
            ]);
        }
        $strQty = implode(",", $arrQty);
        $strGid = 'gid-' . $id . '-' . $gtype;
        $result = $shopify->call([
            'METHOD' => 'POST',
            'URL' => '/admin/metafields.json',
            'DATA' => [
                'metafield' => [
                    "namespace" => "discount",
                    "key" => $strGid,
                    "value" => $strQty,
                    "value_type" => "string"
                ]
            ]
        ]);

        if ($res > 0) {
            //session()->regenerate();
            session([
                'success' => 'Successfully Completed!'
            ]);
            return redirect()->action('Discount@products');
        } else {
            return redirect()->action('Discount@creategroup');
        }
    }

    public function collections(Request $request)
    {
        $session = session()->all();
        if (empty($session['shopurl'])) {
            return redirect('/');
        }
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);

        $groups = DB::table('groups')->get()->where('shop', $session['shopId']);
        $arr = $groups->toArray();
        $productGroup = DB::table('collection')->orderBy('id', 'desc')
                ->select('*')
                ->get();
        $groupAdded = $productGroup->toArray();


        $pageNumber = 1;
        if ($request->has('page')) {
            $pageNumber = $request->input('page');
        }
        $filter = 'all';
        if (isset($_GET['filter_by'])) {
            $filter = $_GET['filter_by'];
        }

        if($filter == 'discount' ){
            $all_ids = array();
            $smart_collection_ids = array();
            $custom_collection_ids = array();
            foreach($groupAdded as $collection){
             array_push($all_ids,$collection->collection_id);  
            }
            $all_ids = array_unique($all_ids);
            $smartresult = $shopify->call([
                'METHOD' => 'GET',
                'URL' => '/admin/smart_collections.json?page=' . $pageNumber.'&fields=id&limit=250',
            ]);
            foreach($smartresult->smart_collections as $smart_collection){
                if (in_array($smart_collection->id, $all_ids)){
                    array_push($smart_collection_ids,$smart_collection->id); 
                }
            }

            $customresult = $shopify->call([
                'METHOD' => 'GET',
                'URL' => '/admin/custom_collections.json?page=' . $pageNumber.'&fields=id&limit=250',
            ]);
            foreach($customresult->custom_collections as $custom_collection){
                if (in_array($custom_collection->id, $all_ids)){
                    array_push($custom_collection_ids,$custom_collection->id); 
                }
            }

            $allresult = (object) array();
            $allresults = (object) array();
            if (!empty($smart_collection_ids) && !empty($custom_collection_ids)) {
                $customresult = $shopify->call([
                    'METHOD' => 'GET',
                    'URL' => '/admin/custom_collections.json?ids='.join(",",$custom_collection_ids),
                ]);
                $smartresult = $shopify->call([
                    'METHOD' => 'GET',
                    'URL' => '/admin/smart_collections.json?ids='.join(",",$smart_collection_ids),
                ]);
                 $allresult = array_merge($smartresult->smart_collections, $customresult->custom_collections);
            } else {
                if (!empty($smart_collection_ids)) {
                    $smartresult = $shopify->call([
                        'METHOD' => 'GET',
                        'URL' => '/admin/smart_collections.json?ids='.join(",",$smart_collection_ids),
                    ]);
                    $allresult = $smartresult->smart_collections;
                }
                if (!empty($custom_collection_ids)) {
                    $customresult = $shopify->call([
                        'METHOD' => 'GET',
                        'URL' => '/admin/custom_collections.json?ids='.join(",",$custom_collection_ids),
                    ]);
                    $allresult = $customresult->custom_collections;
                }
            }
            $allresults->collection_listings = $allresult;
            $collects = $allresults->collection_listings;
        }else{
        // for product fetch defaul first page
        $countProducts = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/collects/count.json'
        ]);
        $customresult = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/custom_collections.json?page=' . $pageNumber,
        ]);
        $smartresult = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/smart_collections.json?page=' . $pageNumber,
        ]);

        $allresult = (object) array();
        $allresults = (object) array();
        if (!empty($smartresult) && !empty($customresult)) {
            $allresult = array_merge($smartresult->smart_collections, $customresult->custom_collections);
        } else {
            if (!empty($smartresult)) {
                $allresult = $smartresult->smart_collections;
            }
            if (!empty($customresult)) {
                $allresult = $customresult->custom_collections;
            }
        }
        $allresults->collection_listings = $allresult;
        $collects = $allresults->collection_listings;
    }
        $data = array(
            'collects' => $collects,
            'groups' => $arr,
            'shop' => $session['shopurl'],
            'groupAlreadyAdded' => $groupAdded,
            'filter' => $filter
        );
        return view('collections')->with($data);
    }

    public function collection_mf(Request $request)
    {
        $session = session()->all();
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);
        $collects = $request->input('selectedColl'); // get prod ids and discount group
        $groups = $request->input('discountGroup');
        $result = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/collections/' . $collects . '/metafields.json'
        ]);
        $cmt = $result->metafields;
        foreach ($cmt as $key => $value) {
            if ($value->namespace == 'collects') {
                $result = $shopify->call([
                    'METHOD' => 'DELETE',
                    'URL' => '/admin/metafields/' . $value->id . '.json'
                ]);
            }
        }

        $discount = DB::table('discount')->get()->where('gid', $groups);
        $type = DB::table('groups')->get()->where('id', $groups);
        $arr = $type->toArray();
        foreach ($arr as $key => $row) {
            $gtype = $row->grouType;
            $gtitle = $row->groupTitle;
            $gid = $row->id;
        }
        // store prod ids in db
        DB::table('collection')->insert([
            'collection_id' => $collects,
			'shop' => $session['shopId'],
            'discount_grp' => $gid
        ]);
        foreach ($discount as $user) {
            $val = $user->value;
            $qty = $user->qty;
            $gid = $user->gid;
            $result = $shopify->call([
                'METHOD' => 'POST',
                'URL' => '/admin/collections/' . $collects . '/metafields.json',
                'DATA' => [
                    'metafield' => [
                        "namespace" => "collects",
                        "key" => 'discount',
                        "value" => $gid,
                        "value_type" => "integer"
                    ]
                ]
            ]);
        }
        //session()->regenerate();
        session([
            'success' => 'Successfully Completed!'
        ]);
        echo "true";
    }

    public function groups()
    {
        $session = session()->all();
        $res = DB::table('groups')->join('discount', 'groups.id', '=', 'discount.gid')
                ->select('*')
                ->where('groups.shop', '=', $session['shopId'])
                ->get();
        $data = array(
            'data' => $res
        );
        return view('groups')->with($data);
    }

    public function productGroup(Request $request)
    {
        $session = session()->all();
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);
        $result = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/products/' . $request->pid . '/metafields.json'
        ]);
        $res = $result->metafields;
        $html = 0;
        foreach ($res as $key => $value) {
            if ($value->namespace == 'inventory') {
                $gid = $value->value;
                $groups = DB::table('groups')->get()->where('shop', $session['shopId']);
                $arr = $groups->toArray();
                $html = '<span class="left">Discount Groups</span><select class="form-control" id="sel1" name="discountGroup[]">
			<option value="0">Choose discount Group</option>';
                foreach ($arr as $key => $value) {
                    if ($value->id == $gid) {
                        $html .= '<option  selected="selected" value="' . $value->id . '">' . $value->groupTitle . '</option>';
                    } else {
                        $html .= '<option   value="' . $value->id . '">' . $value->groupTitle . '</option>';
                    }
                }
                $html .= '</select><span><a href="#" class="createGroup">Create Discount Group</a></span>';
            } else {
                $html = 0;
            }
        }
        echo json_encode($html);
    }

    public function collectsGroup(Request $request)
    {
        $session = session()->all();
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);
        $result = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/collections/' . $request->cid . '/metafields.json'
        ]);
        $res = $result->metafields;
        $html = 0;
        foreach ($res as $key => $value) {
            if ($value->namespace == 'collects') {
                $gid = $value->value;
                $groups = DB::table('groups')->get()->where('shop', $session['shopId']);
                $arr = $groups->toArray();
                $html = '<span class="left">Discount Groups</span><select class="form-control" id="sel1" name="discountGroup[]">
							<option value="0">Choose discount Group</option>';
                foreach ($arr as $key => $value) {
                    if ($value->id == $gid) {
                        $html .= '<option  selected="selected" value="' . $value->id . '">' . $value->groupTitle . '</option>';
                    } else {
                        $html .= '<option   value="' . $value->id . '">' . $value->groupTitle . '</option>';
                    }
                }
                $html .= '</select><span><a href="#" class="createGroup">Create Discount Group</a></span>';
            } else {
                $html = 0;
            }
        }
        echo json_encode($html);
    }

    public function searchProducts(Request $request)
    {
        $session = session()->all();
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);
        $title = $request->title;
        $title = str_replace('%', '&', $title);
        $title = str_replace(' ', '%20', $title);
        $result = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/products.json?title=' . $title
        ]);
        echo json_encode($result);
    }

    public function disableDiscount(Request $request)
    {
        $session = session()->all();
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);
        $products = $request->input('selectedProd'); // get prod ids and discount group
        $groups = $request->input('discountGroup');
        $result = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/products/' . $products . '/metafields.json'
        ]);
        DB::table('products')->where('product_id', '=', $products)->delete(); // delete the prodID from DB
        $cmt = $result->metafields;
        foreach ($cmt as $key => $value) {
            if ($value->namespace == 'inventory') {
                $result = $shopify->call([
                    'METHOD' => 'DELETE',
                    'URL' => '/admin/metafields/' . $value->id . '.json'
                ]);
            }
        }
        //session()->regenerate();
        session([
            'success' => 'Successfully Completed!'
        ]);
        echo "true";
    }

    public function disableCollsDiscount(Request $request)
    {
        $session = session()->all();
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);
        $products = $request->input('selectedColl'); // get prod ids and discount group
        $groups = $request->input('discountGroup');
        $result = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/collections/' . $products . '/metafields.json'
        ]);
        DB::table('collection')->where('collection_id', '=', $products)->delete(); // delete the prodID from DB
        $cmt = $result->metafields;
        foreach ($cmt as $key => $value) {
            if ($value->namespace == 'collects') {
                $result = $shopify->call([
                    'METHOD' => 'DELETE',
                    'URL' => '/admin/metafields/' . $value->id . '.json'
                ]);
            }
        }
        //session()->regenerate();
        session([
            'success' => 'Successfully Completed!'
        ]);
        echo "true";
    }

    public function applyInBulk(Request $request)
    {
        $session = session()->all();
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);
        $products = $request->selectedProducts;
        $groups = $request->selectedDiscGroup;
        foreach ($products as $key => $value) {
            $result = $shopify->call([
                'METHOD' => 'GET',
                'URL' => '/admin/products/' . $value . '/metafields.json'
            ]);

            $cmt = $result->metafields;
            foreach ($cmt as $key => $val) {
                if ($val->namespace == 'inventory') {
                    $result = $shopify->call([
                        'METHOD' => 'DELETE',
                        'URL' => '/admin/metafields/' . $val->id . '.json'
                    ]);
                }
            }
        }

        $discount = DB::table('discount')->get()->where('gid', $groups);
        $type = DB::table('groups')->get()->where('id', $groups);
        $arr = $type->toArray();
        foreach ($arr as $key => $row) {
            // code...
            $gtype = $row->grouType;
            $gtitle = $row->groupTitle; // group title
            $gid = $row->id;
        }
        foreach ($products as $key => $row) {

            // store prod ids in db
            DB::table('products')->insert([
                'product_id' => $row,
                'discount_grp' => $gid,
                'shop' => $session['shopId']
            ]);
            foreach ($discount as $user) {
                $val = $user->value;
                $qty = $user->qty;
                $gid = $user->gid;

                $result = $shopify->call([
                    'METHOD' => 'POST',
                    'URL' => '/admin/products/' . $row . '/metafields.json',
                    'DATA' => [
                        'metafield' => [
                            "namespace" => "inventory",
                            "key" => "discount",
                            "value" => $gid,
                            "value_type" => "integer"
                        ]
                    ]
                ]);
            }
        }
        //session()->regenerate();
        session([
            'success' => 'Successfully Completed!'
        ]);
        return redirect()->action('Discount@products');
    }

    public function applyBulkCollcts(Request $request)
    {
        $session = session()->all();
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);

        $products = $request->selectedCollections;
        $groups = $request->selectedDiscGroup;
        foreach ($products as $key => $value) {
            $result = $shopify->call([
                'METHOD' => 'GET',
                'URL' => '/admin/collections/' . $value . '/metafields.json'
            ]);

            $cmt = $result->metafields;
            foreach ($cmt as $key => $val) {
                if ($val->namespace == 'collects') {
                    $result = $shopify->call([
                        'METHOD' => 'DELETE',
                        'URL' => '/admin/metafields/' . $val->id . '.json'
                    ]);
                }
            }
        }

        $discount = DB::table('discount')->get()->where('gid', $groups);
        $type = DB::table('groups')->get()->where('id', $groups);
        $arr = $type->toArray();
        foreach ($arr as $key => $row) {
            // code...
            $gtype = $row->grouType;
            $gtitle = $row->groupTitle;
            $gid = $row->id;
        }
        foreach ($products as $key => $row) {
            // store prod ids in db
            DB::table('collection')->insert([
                'collection_id' => $row,
                'discount_grp' => $gid,
                'shop' => $session['shopId']
            ]);
            foreach ($discount as $user) {
                $val = $user->value;
                $qty = $user->qty;
                $gid = $user->gid;

                $result = $shopify->call([
                    'METHOD' => 'POST',
                    'URL' => '/admin/collections/' . $row . '/metafields.json',
                    'DATA' => [
                        'metafield' => [
                            "namespace" => "collects",
                            "key" => "discount",
                            "value" => $val,
                            "value_type" => "integer"
                        ]
                    ]
                ]);
            }
        }
        session([
            'success' => 'Successfully Completed!'
        ]);
        return redirect()->action('Discount@products');
    }

    public function editGroup(Request $request)
    {
        $gid = $request->g;
        $session = session()->all();
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);

        $res = DB::table('groups')->join('discount', 'groups.id', '=', 'discount.gid')
                ->where('groups.id', '=', $gid)
                ->select('*')
                ->get();
        $arr = $res->toArray();
        $data = array(
            'data' => $arr
        );
        return view('editgroup')->with($data);
    }

    public function editGroups(Request $request)
    {
        $grouptitle = $request->gtitle;
        $gtype = $request->gtype;
        $qty = $request->qty;
        $valOfDis = $request->val;
        $gid = $request->groupdid;

        $session = session()->all();
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);

        $result = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/metafields.json'
        ]);
        $mfID = '';
        $cmt = $result->metafields;
        foreach ($cmt as $key => $val) {
            if ($val->namespace == 'discount') {
                $arrId = explode("-", $val->key);
                $gidd = $arrId[1];
                if ($gidd == $gid) {
                    $result = $shopify->call([
                        'METHOD' => 'DELETE',
                        'URL' => '/admin/metafields/' . $val->id . '.json'
                    ]);
                }
            }
        }

        DB::table('groups')->where('id', $gid)->update([
            'groupTitle' => $grouptitle,
            'grouType' => $gtype
        ]);

        DB::table('discount')->where('gid', '=', $gid)->delete();
        $arrQty = [];
        foreach ($qty as $key => $quantity) {
            $value = $valOfDis[$key];
            $qty = $quantity;
            $qv = $value . '-' . $qty;
            array_push($arrQty, $qv);
            $res = DB::table('discount')->insert([
                [
                    'gid' => $gid,
                    'qty' => $qty,
                    'value' => $value
                ]
            ]);
        }

        $strQty = implode(",", $arrQty);
        $strGid = 'gid-' . $gid . '-' . $gtype;
        $result = $shopify->call([
            'METHOD' => 'POST',
            'URL' => '/admin/metafields.json',
            'DATA' => [
                'metafield' => [
                    "namespace" => "discount",
                    "key" => $strGid,
                    "value" => $strQty,
                    "value_type" => "string"
                ]
            ]
        ]);

        if ($res > 0) {
            //session()->regenerate();
            session([
                'success' => 'Successfully Completed!'
            ]);
            return redirect()->action('Discount@products');
        } else {
            return redirect()->action('Discount@creategroup');
        }
    }

    public function deleteGroups(Request $request)
    {
        $groupdid = $request->gid;
        $session = session()->all();
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);

        $groupName = DB::table('groups')
                ->where('id', $groupdid)
                ->select('groupTitle')
                ->get();

        $groupName = $groupName->toArray();
        $gname = $groupName[0]->groupTitle;
        DB::table('groups')->where('id', '=', $groupdid)->delete(); // delete the grp from DB
        DB::table('discount')->where('gid', '=', $groupdid)->delete(); // delete the grp from DB

        DB::table('products')->where('discount_grp', '=', $gname)->delete(); // delete the grp from DB
        DB::table('collection')->where('discount_grp', '=', $gname)->delete(); // delete the grp from DB

        $result = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/metafields.json'
        ]);
        $mfID = '';
        $cmt = $result->metafields;
        foreach ($cmt as $key => $val) {
            if ($val->namespace == 'discount') {
                $arrId = explode("-", $val->key);
                $gidd = $arrId[1];
                if ($gidd == $groupdid) {
                    $result = $shopify->call([
                        'METHOD' => 'DELETE',
                        'URL' => '/admin/metafields/' . $val->id . '.json'
                    ]);
                }
            }
        }
        return true;
    }

    public function SnippetsPage(Request $request)
    {
        $session = session()->all();
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);

        return view('snippets')->with('a');
    }

    public function AddSnippts(Request $request)
    {
        $session = session()->all();
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);

        $checkout = '"checkout"';
        $add_cart = '"/cart/add"';
        $total = '"cartSubtotal"';
        $discount_val = '"discount_val"';
        $cart_total_val = '"cart_total_val"';
        $val = "value='";
        $dot = "'";
        $cart = '"cart"';
        $dd = '"dd_discount discount_val"';
        $cd = '"dd_discount cart_total_val"';
        $dd_c='"#discount_val"';
        $cd_c = '"#cart_total_val"';

        $d1 =  "id='discount_val' value='";
        $d2 =  "id='cart_total_val' value='";
        
        $res = "<!-- Last Code Update: ".date("Y/m/d")."-->
        {% assign css = shop.metafields.stylesheet.discount %}
        {% assign css_enable = shop.metafields.stylesheet.customCssEnable %}
        {% assign table_template = shop.metafields.stylesheet.template %}
        {% assign dtype = 0 %}
        {% capture table %}
        {% for field in product.metafields.inventory %}
        {% assign group =  field | last  %}
        {% for fields in shop.metafields.discount %}
        {% assign d_key =  fields | first | split: '-' %}
        {% assign gid 	= 	d_key[1] %}
        {% assign type 	= 	d_key[2] %}
        {% if gid contains group %}
        {% assign qty =  fields | last | split: ',' %}
        {% for qtydisc in qty %} 
        {% if type == '1' %}
        {% assign ammounttype = '$' %}
        {% else %}
        {% assign ammounttype = '%' %} 
        {% endif %}
        <tr>
          <td>Buy {{ qtydisc | split:'-' | last   }} Items </td>
          <td>Get {{ qtydisc | split:'-' | first }} {{ammounttype}}  discount</td> 
        </tr>
        {% assign dtype = 1 %}
        {% endfor %}
        {% endif %}
        {% endfor %}
        {% endfor %}
        {% assign items = '' %}
        {% assign getdis = '' %}
        {% assign disc = '' %}
        {% assign prod = '' %}
        {% if dtype != 1  %}
        {% for collection in product.collections %}
        {% for field in collection.metafields.collects %}
        {% assign group =  field | last  %}
        {% for fields in shop.metafields.discount %}
        {% assign d_key =  fields | first | split: '-' %}
        {% assign gid 	= 	d_key[1] %}
        {% assign type 	= 	d_key[2] %}
        {% if gid contains group %} 
        {% assign qty =  fields | last | split: ',' %}
        {% for qtydisc in qty %} 
        {% if type == '1' %}
        {% assign ammounttype = '$' %}
        {% else %}
        {% assign ammounttype = '%' %} 
        {% endif %}
        {% assign items =  qtydisc  |  split: '-' | last   %}
        {% assign getdis =   qtydisc | split: '-' | first  %}
        {% assign prod = prod  | append: ',' | append:items %}
        {% assign disc = disc  | append: ',' | append:getdis %}
        {% endfor %}
        {% endif %} 
        {% endfor %}
        {% endfor %}
        {% endfor %}
        {% endif %} 
        {% assign items = prod | split: ',' %}
        {% assign qtydisc = disc | split: ',' %}
        {% assign  items =  items | uniq  %}
        {% assign  disc  =  qtydisc | uniq %}
        {% assign  i  =  0 %}
        {% for item in items %}
        {% if forloop.first == true %} {% continue %} {% endif %}
        {% assign  i  =  i | plus:1 %}
        <tr class='collects'>
          <td>Buy {{item}} Items </td>
          <td>Get {{ disc[i] }} {{ammounttype}} discount</td> 
        </tr>
        {% assign dtype = 2 %}
        {% endfor %}
        {% if dtype != 2 and dtype != 1  %}
        {% for field in shop.metafields.discountOnAll %}
        {% assign group =  field | last  %}
        {% for fields in shop.metafields.discount %}
        {% assign d_key =  fields | first | split: '-' %}
        {% assign gid 	= 	d_key[1] %}
        {% assign type 	= 	d_key[2] %}
        {% if gid contains group %}
        {% assign qty =  fields | last | split: ',' %}
        {% for qtydisc in qty %} 
        {% if type == '1' %}
        {% assign ammounttype = '$' %}
        {% else %}
        {% assign ammounttype = '%' %} 
        {% endif %}
        <tr>
          <td>Buy {{ qtydisc | split:'-' | last   }} Items </td>
          <td>Get {{ qtydisc | split:'-' | first }} {{ammounttype}}  discount</td> 
        </tr>
        {% endfor %}
        {% endif %}
        {% endfor %}
        {% endfor %}
        {% endif %}
        {% endcapture %}
        {% if table != blank %}
        <style>
          .design-table td, .design-table th, .design-table tr {
            text-align:  center;
            border-color: #B3B3B3;
            padding: 18px;
          }
          .design-table td {
            background: #F5F5F5;
            width: 50%;
          }
          .design-template1>table>tbody>tr:nth-child(1) {
            background: #6f524c;
            text-align:  center;
            color:  white;
          }
          .design-template2>table>tbody>tr:nth-child(1) {
            background-image: -moz-linear-gradient( 90deg, rgb(227,227,227) 0%, rgb(244,244,244) 100%);
            background-image: -webkit-linear-gradient( 90deg, rgb(227,227,227) 0%, rgb(244,244,244) 100%);
            background-image: -ms-linear-gradient( 90deg, rgb(227,227,227) 0%, rgb(244,244,244) 100%);
            text-align:  center;
            color:  black;
          }
          .design-template3>table>tbody>tr:nth-child(1) {
            background: #182E49;
            color:  white;
          }
          .design-no>table>tbody>tr:nth-child(1) {
            background: black;
            color: white;
          }
        </style>
        
        
        {% if css != blank and css_enable == 'yes' and table_template  != 'no' %}
        <style>
          {{css}}
        </style>
        {% endif %}
        
        
        <div class='discout_table' style='display:none;'>
          <div class='design-{{table_template}} design-table'>
            <table style='width:100%;margin-top:2%;' >
              <tr>
                <th>QTY</th>
                <th>DISCOUNT</th>   
              </tr>
              {{table}}
            </table>
          </div>
        </div>
        {% if template contains 'product' %}
        <script>
          $('form[action=".$add_cart."]').append($('.discout_table').html());
        </script>
         {% endif %}
        {% endif %}
        
        <div class='loader-ajax' style='display:none;'>
          <div class='checkoutajax'></div>
        </div>
        
        <script>
          $(document).ready(function(){
        
            //local stoage to store coupon code random number
            var randomstring = '';
            function randomString() {
              var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz';
              var string_length = 3;
        
              for (var i=0; i<string_length; i++) {
                var rnum = Math.floor(Math.random() * chars.length);
                randomstring += chars.substring(rnum,rnum+1);
              }
        
              return randomstring;
            }
            var randomNumber =  randomString();
            if (typeof(Storage) !== 'undefined') {
        
              var randomCode =  localStorage.getItem('couponcode');
        
              if(randomCode == null )
              {
                // Store
                localStorage.setItem('couponcode',randomstring);
        
              }
            } else {
        
              console.log('Sorry! No Web Storage support..');
            }
          });
        
        </script>
        
        
        <!-- APP CODE FOR DISCOUNT -->
        <script>var productIDS = [];</script>
        {% assign totaldiscount  = 0 %}
        {% assign finalcalc  = 0 %}
        {% assign cartPrice =  cart.total_price | divided_by: 100.0 %}
        {% assign ammounttype = 0 %}
        {% assign type = 0 %}
        {% for item in cart.items %}
        <script>productIDS.push({{item.product.id}});</script>
        {% assign allstoremtf = ''   %}
        {% assign ammounttype = ''   %}
        {% for field in shop.metafields.discountOnAll %}
        {% assign allstoremtf = field | first   %}
        {% assign group =  field | last  %}   <!-- group -->
        {% endfor %}
        
        <!-- for all store discount first -->
        {% if allstoremtf != '' %}   <!--  //when not empty full store discount -->
        
        {% for fields in shop.metafields.discount %}
        {% assign d_key =  fields | first | split: '-' %}
        {% assign gid 	= 	d_key[1] %}
        {% assign type 	= 	d_key[2] %}
        {% if gid contains group %}
        {% assign qty =  fields | last | split: ',' %}
        {% for qtydisc in qty %}
        
        {% assign qtyy = qtydisc  |  split: '-' | last   %}
        {% assign qtyy = qtyy  | plus:0   %}
        
        {% assign discount = qtydisc | split: '-' | first |  plus:0.0 %}
        
        {% if item.quantity    >= qtyy    %}
        {% if type == '1' %}    {% assign totaldiscount =  discount  %}
        {% else %}		{% assign totaldiscount  = 0 %}		{% assign totaldiscount  = item.line_price  | times: discount   | divided_by: 10000 %}
        {% endif %}
        
        {% endif %}
        {% endfor %}
        {% endif %}
        
        {% endfor %}
        {% endif %}
        
        <!-- for collection code -->
        
        {% for collection in item.product.collections %}
        {% for field in collection.metafields.collects %}
        {% assign group =  field | last  %}   <!-- group -->
        {% for fields in shop.metafields.discount %}
        {% assign d_key =  fields | first | split: '-' %}
        {% assign gid 	= 	d_key[1] %}
        {% assign type 	= 	d_key[2] %}
        {% if gid contains group %}
        {% assign qty =  fields | last | split: ',' %}
        {% for qtydisc in qty %}
        
        {% assign qtyy = qtydisc  |  split: '-' | last   %}
        {% assign qtyy = qtyy  | plus:0   %}
        
        {% assign discount = qtydisc | split: '-' | first |  plus:0.0 %}
        
        {% if item.quantity    >= qtyy    %}
        {% if type == '1' %}    {% assign totaldiscount =  discount  %}
        {% else %}		{% assign totaldiscount  = 0 %}		{% assign totaldiscount  = item.line_price  | times: discount   | divided_by: 10000 %}
        {% endif %}
        
        {% endif %}
        {% endfor %}
        {% endif %}
        
        {% endfor %}
        {% endfor %}
        {% endfor %}
        
        
        <!-- for product code -->
        {% for field in item.product.metafields.inventory %}
        {% assign group =  field | last  %}   <!-- group -->
        {% for fields in shop.metafields.discount %}
        {% assign d_key =  fields | first | split: '-' %}
        {% assign gid 	= 	d_key[1] %}
        {% assign type 	= 	d_key[2] %}
        {% if gid contains group %}
        {% assign qty =  fields | last | split: ',' %}
        {% for qtydisc in qty %}
        
        {% assign qtyy = qtydisc  |  split: '-' | last   %}
        {% assign qtyy = qtyy  | plus:0   %}
        
        {% assign discount = qtydisc | split: '-' | first |  plus:0.0 %}
        
        {% if item.quantity    >= qtyy    %}
        {% if type == '1' %}    {% assign totaldiscount =  discount  %}
        {% else %}		{% assign totaldiscount  = 0 %}		{% assign totaldiscount  = item.line_price  | times: discount   | divided_by: 10000 %}
        {% endif %}
        
        {% endif %}
        {% endfor %}
        {% endif %}
        
        {% endfor %}
        {% endfor %}
        {% assign finalcalc  = finalcalc | plus:totaldiscount %}
        {% assign totaldiscount  = 0 %}
        {% endfor %}
        
        {% assign final_discount = finalcalc | ceil | round: 2%}
        {% if final_discount > 0 %}
        {% assign cartTotal = cart.total_price | append:'.0' |  divided_by:100 | minus:final_discount %}
        
        <input type='hidden' id='discount_val' value='{{final_discount}}'>
        <input type='hidden' id='cart_total_val' value='{{cartTotal}}'>
		{% if template contains 'cart' %}
        <script>
          $('.cartSubtotal span:first-child').addClass('fullprice');
          $('".$total."').append('<div class=".$dd."><label>Bulk Order Discount:</label>$'+$(".$dd_c.").val()+'</div><div class=".$cd."><label>Total:</label>$'+$(".$cd_c.").val()+'</div>');
        </script>
        {% endif %}
		{% endif %}
        
        <script>
          var lastcode = localStorage.getItem('lastCode');
          $('#af_discount').append({{cartTotal}}+'$');
          $('input[name=".$checkout."], button[name=".$checkout."]').click(function(e){
            e.preventDefault();
        
            $(this).addClass('disable').attr('disable');
            $('.loader-ajax').show();
        
            var coupCode 			= localStorage.getItem('couponcode');
            var discountPrice 		= {{final_discount}};
            var lastcode			= localStorage.getItem('lastCode');
            var lastPriceRule 		= localStorage.getItem('lastPriceRule');
            var lastcodeid 			= localStorage.getItem('lastDiscId');
        
        
            if(discountPrice > 0)
            {
              $('body.template-cart .btnVelaOne').addClass('checkoutajax'); //for loader css
              $.get('https://discounts.cloudfireapps.com/discount?shopp={{shop.permanent_domain}}&&code='+coupCode+'&&dp='+discountPrice+'&&prodid='+productIDS+'&&lastcode='+lastcode+'&&lastPriceRule='+lastPriceRule+'&&lastcodeid='+lastcodeid).done(function (data) {
                    var obj 		 = JSON.parse(data);
              var ccode        = obj.couponcode;
              var cid     	 = obj.lastPriceRule;
              var lastcodeid   = obj.lastcodeid;
              localStorage.setItem('lastCode',ccode);
              localStorage.setItem('lastPriceRule',cid);
              localStorage.setItem('lastDiscId',lastcodeid);
              window.location.href = '/checkout'+'/?discount=' + ccode;
              //   	 setTimeout(function(){    }, 800);
            });
          }
                                                                     else
                                                                     {
                                                                     window.location.href = '/checkout/?discount=+';
                                                                     }
        
                                                                     });
        
        </script>
        <script>
          $(document).ready(function(){
            $(document).on('click','.velaQtyAdjust',function(){
              $('.btnUpdateCart').click();
            });
            $(document).on('change','.velaQtyNum',function(){
              $('.btnUpdateCart').click();
            });
          });
        </script>
        
        <style>
          .fullprice {
            text-decoration: line-through;
            font-size: 21px;
          }
          .loader-ajax {
            position: fixed;
            top: 0;
            left: 0;
            background: rgba(255,255,255,0.6);
            width: 100%;
            height: 100%;
          }
          .loader-ajax .checkoutajax {
            margin-bottom: 0;
            background-image: url(https://cdn.shopify.com/s/files/1/1706/0693/files/preloader.gif?9342386391074454852);
            background-position: center;
            background-repeat: no-repeat;
            background-size: 35px;
            width: 133px;
            height: 39px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
          }
          input.disable[name='checkout'], button.disable[name='checkout'] {
            pointer-events:none;
          }
          .dd_discount label {
            display:  inline-block;
            margin-right:  10px;
            font-weight:  normal;
          }
          .dd_discount {
            font-weight:  bold;
            font-size:  16px;
          }
        </style>
        
        <script>
        
          $('form[action=".$add_cart."]').submit(function(){
            setTimeout(function(){ get_discount_val() }, 3000);
          });
		  
		  
		  $(document).on('click','.jsDrawerOpenRight button.qtyAdjust',function(){
            setTimeout(function(){ get_discount_val() }, 3000);
          });
		  
          // $('body').on('click','a[href*=".$cart."]',function(){
            // setTimeout(function(){ get_discount_val() }, 3000);
          // });  
		  //      specific code for microsite's products
			var div1 = $('#pageContainer');
			var observer = new MutationObserver(function(mutations) {
			  mutations.forEach(function(mutation) {
				if (mutation.attributeName === 'class') {
				  var attributeValue = $(mutation.target).prop(mutation.attributeName);
				  if(attributeValue == 'isMoved is-transitioning'){
					setTimeout(function(){ get_discount_val() }, 3000);
				  }

				}
			  });
			});
			  observer.observe(div1[0], {
				attributes: true
			  });
		  // 		microsite's code end
        
          function get_discount_val(){
            $.ajax({
              url:  '/cart',
              type: 'GET',
              dataType: 'html',
              success: function (data) {
				var elements = $(data);
                var discount = elements.filter('input#discount_val').val();
                var cart_val = elements.filter('input#cart_total_val').val();
				$('".$total."').parent().find('.dd_discount').remove();
                if(discount){
                  $('".$total."').after('<div class=".$dd."><label>Bulk Order Discount:</label>$'+discount+'</div><div class=".$cd."><label>Total:</label>$'+cart_val+'</div>');
                }
				
                // var discount = data.split(".$d1.")[1].split(".$dot.")[0];
                // var cart_val = data.split(".$d2.")[1].split(".$dot.")[0];

                // $('div[class*=".$total."]').find('.dd_discount').remove();
                // if(discount){
                //   $('div[class*=".$total."]').append('<div class=".$dd."><label>Bulk Order Discount:</label>$'+discount+'</div><div class=".$cd."><label>Total:</label>$'+cart_val+'</div>');
                // }
              }
            });
          }
        </script>";
    $published_theme_id = '';
    $themes_collection = $shopify->call([
        'METHOD' => 'GET',
        'URL' => '/admin/themes.json'
    ]);
    foreach($themes_collection->themes as $theme){
        if($theme->role == 'main'){
            $published_theme_id = $theme->id;
        }
    }
        $asset_list = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/themes/'.$published_theme_id.'/assets.json'
        ]);
        
        $asset_status = false;
        $cart_template = false;

        foreach($asset_list->assets as $asset){
            if($asset->key == 'snippets/discount_calculation.liquid'){
                $asset_status = true;
            }
        }

        if($asset_status == false){
        $result = $shopify->call([
            'METHOD' => 'PUT',
            'URL' => '/admin/themes/'.$published_theme_id.'/assets.json',
            'DATA' => [
                'asset' => [
                    "key" => "snippets/discount_calculation.liquid",
                    "value" => $res
                ]
            ]
        ]);
    }

        $key = 'layout/theme.liquid';
        $cart_val = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/themes/'.$published_theme_id.'/assets.json?asset[key]='.$key.'&theme_id='.$published_theme_id
        ]);

        $cart_template_content = $cart_val->asset->value;
        if (strpos($cart_template_content, "{% include 'discount_calculation' %}") === false) {
            $result = $shopify->call([
                'METHOD' => 'PUT',
                'URL' => '/admin/themes/'.$published_theme_id.'/assets.json',
                'DATA' => [
                    'asset' => [
                        "key" => $key,
                        "value" => $cart_val->asset->value."{% include 'discount_calculation' %}"
                    ]
                ]
            ]);
        }
        sleep(2);
        return redirect()->action('Discount@products');
    }

    public function CustomCss(Request $request){

        $session = session()->all();
        $shopify = App::make('ShopifyAPI', [
                    'API_KEY' => API_KEY,
                    'API_SECRET' => API_SECRET,
                    'SHOP_DOMAIN' => $session['shopurl'],
                    'ACCESS_TOKEN' => $session['accessToken']
        ]);
        
        if (isset($_GET['customCss'])) {
            $filter = $_GET['customCss'];
            if (empty($filter)) {
                $res = $shopify->call([
                    'METHOD' => 'GET',
                    'URL' => '/admin/metafields.json'
                ]);
                $mtf = $res->metafields;
                $deletemtfid = '';
                foreach ($mtf as $key => $value) {
                    if ($value->namespace == 'stylesheet') {
                        $deletemtfid = $value->id;
                        $shopify->call([
                            'METHOD' => 'DELETE',
                            'URL' => '/admin/metafields/' . $deletemtfid . '.json'
                        ]);
                    }
                }
            }else{
             $css_metafield = $shopify->call([
                'METHOD' => 'POST',
                'URL' => '/admin/metafields.json',
                'DATA' => [
                    'metafield' => [
                        "namespace" => "stylesheet",
                        "key" => "discount",
                        "value" => $filter,
                        "value_type" => "string"
                    ]
                ]
            ]);
        }
    }

    if (isset($_GET['template'])) {
        $filter = $_GET['template'];
        $template_field = $shopify->call([
            'METHOD' => 'POST',
            'URL' => '/admin/metafields.json',
            'DATA' => [
                'metafield' => [
                    "namespace" => "stylesheet",
                    "key" => "template",
                    "value" => $filter,
                    "value_type" => "string"
                ]
            ]
        ]);
    }
    if (isset($_GET['customCssEnable'])) {
        $filter = $_GET['customCssEnable'];
        $template_field = $shopify->call([
            'METHOD' => 'POST',
            'URL' => '/admin/metafields.json',
            'DATA' => [
                'metafield' => [
                    "namespace" => "stylesheet",
                    "key" => "customCssEnable",
                    "value" => $filter,
                    "value_type" => "string"
                ]
            ]
        ]);
    }
        $metafields_list = $shopify->call([
            'METHOD' => 'GET',
            'URL' => '/admin/metafields.json'
        ]);

        $css = '';
        $css_enable = '';
        $template = '';
        foreach($metafields_list->metafields as $metafield){
            if($metafield->namespace = 'stylesheet' && $metafield->key == 'discount'){
                 $css = $metafield->value;
            }
            if($metafield->namespace = 'stylesheet' && $metafield->key == 'customCssEnable'){
                $css_enable = $metafield->value;
           }
           if($metafield->namespace = 'stylesheet' && $metafield->key == 'template'){
            $template = $metafield->value;
       }
        }

            $data = array(
                'css' => $css,
                'cssenable'=> $css_enable,
                'template' => $template,
            );

        return view('customcss')->with($data);
    }
}
