<?php
/**
 * Date: 2019/2/25 Time: 14:49
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LevelRequest;
use App\Repository\Admin\LevelRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class LevelController extends Controller
{
    protected $formNames = ['id', 'level_name'];

    /**
     * @Title: index
     * @Description: 级别管理-级别列表
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

        $data = LevelRepository::list($perPage, $condition);
        if (empty($data))
            return '';

        return view('admin.level.index', [
            'lists' => $data,  //列表数据
        ]);
    }

    /**
     * @Title: create
     * @Description: 级别管理-新增级别
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @Author: 李军伟
     */
    public function create()
    {
        return view('admin.level.add');
    }

    /**
     * @Title: save
     * @Description: 级别管理-保存级别
     * @param LevelRequest $request
     * @return array
     * @Author: 李军伟
     */
    public function save(LevelRequest $request)
    {
        try {
            $data = $request->only($this->formNames);
            LevelRepository::add($data);
            return [
                'code' => 0,
                'msg' => trans('general.createSuccess'),
                'redirect' => true
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
//                'msg' => trans('general.createFailed') . ":" . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前级别已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }

    /**
     * @Title: edit
     * @Description: 级别管理-编辑级别
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @Author: 李军伟
     */
    public function edit($id)
    {
        $info = LevelRepository::find($id);
        return view('admin.level.add', [
            'id' => $id,
            'info' => $info
        ]);
    }

    /**
     * @Title: update
     * @Description: 级别管理-更新级别
     * @param LevelRequest $request
     * @param $id
     * @return array
     * @Author: 李军伟
     */
    public function update(LevelRequest $request, $id)
    {
        $data = $request->only($this->formNames);

        try {
            LevelRepository::update($id, $data);
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
     * @Description: 级别详情
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @Author: 李军伟
     */
    public function info($id)
    {
        $level = LevelRepository::find($id);

        return view('admin.level.info', [
            'id' => $id,
            'level' => $level,
        ]);
    }

    /**
     * @Title: delete
     * @Description: 级别管理-删除级别
     * @param $id
     * @return array
     * @Author: 李军伟
     */
    public function delete($id)
    {
        try {
            LevelRepository::delete($id);
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
