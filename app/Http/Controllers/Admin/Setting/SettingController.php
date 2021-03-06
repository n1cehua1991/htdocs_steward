<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Models\StApp;
use App\Models\StConfigure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PHPUnit\Runner\Exception;
use DB;

class SettingController extends Controller
{

    //基础设置页
    public function index()
    {

        $st_configure = StConfigure::select('data_json')->where('c_id', 1)->first();
        $json_data = json_decode($st_configure->data_json,true);

        $st_app = StApp::get();

        return view('admin/setting/index',[
            'images_data' => $json_data['data'],
            'app_data' => $st_app
        ]);

    }

    //基础设置提交
    public function submit(Request $request)
    {

        $request_data = $request->input();

        //公司logo
        if (!isset($request_data['company']) || empty($request_data['company'])) {
            $request_data['company'] = '/images/admin/app/defalt.png';
        }

        //商品logo
        if (!isset($request_data['goods']) || empty($request_data['goods'])) {
            $request_data['goods'] = '/images/admin/app/defalt.png';
        }

        $st_configure = StConfigure::select('data_json')->where('c_id',1)->first();
        $json_data = json_decode($st_configure->data_json,true);

        $json_data['data']['company_logo'] = $request_data['company'];
        $json_data['data']['goods_logo'] = $request_data['goods'];

        try {

            DB::beginTransaction();

            StConfigure::where('c_id',1)->update(['data_json' => json_encode($json_data)]);

            $app_ids = [];
            foreach ($request_data as $key => $item) {
                if (is_array($item) && sizeof($item) == 3) {
                    StApp::where('id',$item[0])->update(['app_key' => $item[1],'app_secret' => $item[2],'enable' => 1]);
                    $app_ids[] = $item[0];
                }
            }

            StApp::whereNotIn('id', $app_ids)->update(['enable' => 0]);

            DB::commit();
            return ['code'=>200, 'message'=>'操作成功'];

        } catch (Exception $e) {
            DB::rollBack();
            return ['code'=>$e->getCode(), 'message'=>$e->getMessage()];
        }



    }
}
