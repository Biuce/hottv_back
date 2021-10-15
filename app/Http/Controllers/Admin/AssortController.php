<?php
/**
 * Date: 2019/2/25 Time: 14:49
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssortRequest;
use App\Repository\Admin\AssortRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AssortController extends Controller
{
    protected $formNames = ['id', 'assort_name'];

    /**
     * @Title: index
     * @Description: 配套管理-配套列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @Author: 李军伟
     */
    public function index(Request $request)
    {
        $perPage = (int)$request->get('limit', env('APP_PAGE'));
        $this->formNames[] = 'created_at';
        $action = $request->get('action');
        $condition = $request->only($this->formNames);

        $data = AssortRepository::list($perPage, $condition);
        if (empty($data))
            return '';

        return view('admin.assort.index', [
            'lists' => $data,  //列表数据
        ]);
    }

    /**
     * @Title: create
     * @Description: 配套管理-新增配套
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @Author: 李军伟
     */
    public function create()
    {
        return view('admin.assort.add');
    }

    /**
     * @Title: save
     * @Description: 配套管理-保存配套
     * @param AssortRequest $request
     * @return array
     * @Author: 李军伟
     */
    public function save(AssortRequest $request)
    {
        try {
            $data = $request->only($this->formNames);
            AssortRepository::add($data);
            return [
                'code' => 0,
                'msg' => trans('general.createSuccess'),
                'redirect' => true
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
//                'msg' => trans('general.createFailed') . ":" . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前应用已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }

    /**
     * @Title: edit
     * @Description: 配套管理-编辑配套
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @Author: 李军伟
     */
    public function edit($id)
    {
        $info = AssortRepository::find($id);
        return view('admin.assort.add', [
            'id' => $id,
            'info' => $info
        ]);
    }

    /**
     * @Title: update
     * @Description: 配套管理-更新配套
     * @param AssortRequest $request
     * @param $id
     * @return array
     * @Author: 李军伟
     */
    public function update(AssortRequest $request, $id)
    {
        $data = $request->only($this->formNames);

        try {
            AssortRepository::update($id, $data);
            return [
                'code' => 0,
                'msg' => trans('general.updateSuccess'),
                'redirect' => true
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
//                'msg' => $e->getMessage(),
                'msg' => trans('general.updateFailed') . ":" . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前大客户已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }

    /**
     * @Title: info
     * @Description: 配套详情
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @Author: 李军伟
     */
    public function info($id)
    {
        $assort = AssortRepository::find($id);

        return view('admin.assort.info', [
            'id' => $id,
            'assort' => $assort,
        ]);
    }

    /**
     * @Title: delete
     * @Description: 配套管理-删除配套
     * @param $id
     * @return array
     * @Author: 李军伟
     */
    public function delete($id)
    {
        try {
            AssortRepository::delete($id);
            return [
                'code' => 0,
                'msg' => trans('general.deleteSuccess'),
                'redirect' => true
            ];
        } catch (\RuntimeException $e) {
            return [
                'code' => 1,
                'msg' => trans('general.deleteFailed') . ":" . $e->getMessage(),
                'redirect' => false
            ];
        }
    }

}
