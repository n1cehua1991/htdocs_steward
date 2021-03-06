<?php

namespace App\Http\Controllers\Ajax\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User\StUserDevices;

class DeviceController extends Controller
{

    /**
     * 设备工作状态更改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function work(Request $request)
    {

        $device = $request->input('device', '');
        $device_type = $request->input('device_type', 0);
        $work_state = $request->input('work_state', 0);

        if (!ebsig_is_int($work_state, 2)) {
            return response()->json(['code'=>400, 'message'=>'缺少必要的工作状态']);
        }

        $user_device = StUserDevices::where([
            'device'=>$device,
            'type'=>$device_type
        ])->first();

        if (!$user_device) {
            return response()->json(['code'=>404, 'message'=>'设备信息没有找到']);
        }

        StUserDevices::where([
            'device'=>$device,
            'type'=>$device_type
        ])->update(['work_state'=>$work_state]);

        return response()->json(['code'=>200, 'message'=>'ok']);

    }


}

