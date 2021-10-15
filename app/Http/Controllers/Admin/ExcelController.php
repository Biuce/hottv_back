<?php

namespace App\Http\Controllers\Admin;

use PHPExcel;
use PHPExcel_IOFactory;

class ExcelController
{
    //execl模板下载
    public function template_download()
    {
        $objExcel = new PHPExcel();
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
        $objActSheet = $objExcel->getActiveSheet(0);
        $objActSheet->setTitle('会员批量导入模板'); //设置excel的标题
        $objActSheet->setCellValue('A1', '用户id');
        $objActSheet->setCellValue('B1', '昵称');
        $objActSheet->setCellValue('C1', '手机号');

        $baseRow = 2; //数据从N-1行开始往下输出 这里是避免头信息被覆盖
        //默认数据
        $explame_data = array(
            array(
                'user_id' => '1',
                'nickname' => '小明',
                'phone' => '15012345678',
            ),
        );

        foreach ($explame_data as $key => $value) {
            $i = $baseRow + $key;
            $objExcel->getActiveSheet()->setCellValue('A' . $i, $value['user_id']);
            $objExcel->getActiveSheet()->setCellValue('B' . $i, $value['nickname']);
            $objExcel->getActiveSheet()->setCellValue('C' . $i, $value['phone']);
        }


        $objExcel->setActiveSheetIndex(0);
        //4、输出
        $objExcel->setActiveSheetIndex();
        header('Content-Type: applicationnd.ms-excel');
        $time = date('Y-m-d');
        header("Content-Disposition: attachment;filename=会员批量导入模板" . $time . ".xls");
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    }

    public function import_batch_send()
    {
        header("content-type:text/html;charset=utf-8");

        //上传excel文件
        $file = request()->file('file');
        //将文件保存到public/uploads目录下面
        $info = $file->validate(['size' => 1048576, 'ext' => 'xls,xlsx'])->move('./uploads');
        if ($info) {
            //获取上传到后台的文件名
            $fileName = $info->getSaveName();
            //获取文件路径
            $filePath = Env::get('root_path') . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $fileName;
            //获取文件后缀
            $suffix = $info->getExtension();
            //判断哪种类型
            if ($suffix == "xlsx") {
                $reader = \PHPExcel_IOFactory::createReader('Excel2007');
            } else {
                $reader = PHPExcel_IOFactory::createReader('Excel5');
            }
        } else {
            return json(['status' => '1', 'message' => '文件过大或格式不正确导致上传失败-_-!']);
        }
        //载入excel文件
        $excel = $reader->load($filePath, $encode = 'utf-8');
        //读取第一张表
        $sheet = $excel->getSheet(0);
        //获取总行数
        $row_num = $sheet->getHighestRow();
        //获取总列数
        $col_num = $sheet->getHighestColumn();

        $import_data = []; //数组形式获取表格数据
        for ($i = 2; $i <= $row_num; $i++) {
            $import_data[$i]['nickname']  = $sheet->getCell("B" . $i)->getValue();
            $import_data[$i]['phone']  = $sheet->getCell("C" . $i)->getValue();
        }

        if (empty($import_data)) {
            return json(['status' => '1', 'message' => '数据解析失败']);
        }

        //校验手机号是否重复
        $phone_array = array_column($import_data, 'phone');
        $phone_ids = implode(',', $phone_array);
        $result_phone = db('user')
            ->field('phone')
            ->where('phone', 'in', $phone_ids)
            ->select();
        if (!empty($result_phone)) {
            $result_phone_array = array_column($result_phone, 'phone');
            $result_phone_ids = implode(',', $result_phone_array);
            return json(['status' => '3', 'message' => '数据重复', 'result' => $result_phone_ids]);
        }

        //将数据保存到数据库
        $res = db('user')->insertAll($import_data);
        if ($res) {
            return json(['status' => '2', 'message' => '导入成功']);
        } else {
            return json(['status' => '1', 'message' => '提交失败，请刷新重试']);
        }
    }
}