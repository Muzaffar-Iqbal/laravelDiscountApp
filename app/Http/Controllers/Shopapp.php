<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Oseintow\Shopify\Shopify;

header("Access-Control-Allow-Origin: * ");

class Shopapp extends Controller
{

    public function authtoken(Request $request)
    {
        $shop = $request->shop;
        $timestamp = $request->timestamp;
        $signature = $request->signature;
        $code = $request->code;
        $hmac = $request->hmac;
        $accesstoken = $request->accessToken;

        
        // check shop exist or not
        session()->flush();
        session()->regenerate();
        if (DB::table('shop')->where('shop_name', '=', $shop)->exists()) {
            $shopdata = DB::table('shop')->where('shop_name', '=', $shop)->first();
            session(['shopurl' => $shopdata->shop_name]);
            session(['accessToken' => $shopdata->access_token]);
            session(['shopId' => $shopdata->id]);
            // return redirect()->action('Discount@products');
            return redirect()->action('Discount@AddSnippts');
        } else {
            $res = DB::table('shop')->insert(
                    ['shop_name' => $shop, 'access_token' => $accesstoken, 'hmac' => $hmac, 'status' => 1, 'created' => $timestamp]
            );
            if ($res) {

                $shopdata = DB::table('shop')->where('shop_name', '=', $shop)->first();
                session(['shopurl' => $shopdata->shop_name]);
                session(['accessToken' => $shopdata->access_token]);
                session(['shopId' => $shopdata->id]);
                return redirect()->action('Discount@AddSnippts');
            } else {

                return redirect()->route('shopapp');
            }
        }
    }

}
