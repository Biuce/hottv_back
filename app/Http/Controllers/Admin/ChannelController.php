<?php
/**
 * Date: 2019/2/25 Time: 14:49
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Repository\APIHelper;
use Illuminate\Support\Facades\Storage;

class ChannelController extends Controller
{
    /**
     * @Title: index
     * @Description: 渠道管理-渠道列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @Author: 李军伟
     */
    public function index()
    {
        $apiStr = 'channels';
        $api = new APIHelper();
        $res = $api->get($apiStr);
        $data = json_decode($res, true);

        if (empty($data['data']))
            return '';

        return view('admin.channel.index', [
            'lists' => $data['data'],  //列表数据
        ]);
    }

    /**
     * @Title: create
     * @Description: 渠道管理-新增渠道
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @Author: 李军伟
     */
    public function create()
    {
        return view('admin.channel.add');
    }

    /**
     * @Title: save
     * @Description: 渠道管理-保存渠道
     * @param Request $request
     * @return array
     * @Author: 李军伟
     */
    public function save(Request $request)
    {
        try {
            $channel_name = $request->input('channel_name');
            $apiStr = 'channel/create';
            $api = new APIHelper();
            $body = [
                'channel_name' => $channel_name,
            ];
            $res = $api->post($body, $apiStr);
            $data = json_decode($res, true);
            if ($data['msg'] != "success") {
                throw new \Exception(trans('channel.createFailed'));
            }
            return [
                'code' => 0,
                'msg' => trans('general.createSuccess'),
                'redirect' => true
            ];
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false
            ];
        }
    }

    /**
     * @Title: edit
     * @Description: 渠道管理-编辑渠道
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @Author: 李军伟
     */
    public function edit($id)
    {
        $apiStr = 'channel/info?channel_id=' . $id;
        $api = new APIHelper();
        $res = $api->get($apiStr);
        $data = json_decode($res, true);

        return view('admin.channel.add', [
            'id' => $id,
            'info' => $data['data']
        ]);
    }

    /**
     * @Title: update
     * @Description: 渠道管理-更新渠道
     * @param Request $request
     * @param $id
     * @return array
     * @Author: 李军伟
     */
    public function update(Request $request, $id)
    {
        try {
            $channel_name = $request->input('channel_name');
            $apiStr = 'channel/update';
            $api = new APIHelper();
            $body = [
                'channel_id' => $id,
                'channel_name' => $channel_name,
            ];
            $res = $api->post($body, $apiStr);
            $data = json_decode($res, true);
            if ($data['msg'] != "success") {
                throw new \Exception(trans('channel.updateFailed'));
            }
            return [
                'code' => 0,
                'msg' => trans('general.updateSuccess'),
                'redirect' => true
            ];
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false
            ];
        }
    }

    /**
     * @Title: info
     * @Description: 渠道详情
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @Author: 李军伟
     */
    public function info($id)
    {
        $apiStr = 'channel/info?channel_id=' . $id;
        $api = new APIHelper();
        $res = $api->get($apiStr);
        $data = json_decode($res, true);
        return view('admin.channel.info', [
            'channel_id' => $id,
            'info' => $data['data'],
        ]);
    }
}
