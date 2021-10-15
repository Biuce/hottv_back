<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize; // 自动列宽,如果需要自动列宽则把他加入到继承接口中
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;     // 自动注册事件监听器
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;    // 导出 0 原样显示，不为 null
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;    // 在工作表流程结束时会引发事件

class GeneralExport implements FromCollection, WithEvents, WithStrictNullComparison, WithColumnFormatting
{
    // 要导出的数据
    public $data;
    // 总行数
    public $rowNum;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * registerEvents.
     * 事件监听
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            // 生成表单元后处理事件
            AfterSheet::class => function (AfterSheet $event) {
                // 合并单元格
                $event->sheet->getDelegate()->setMergeCells(['A2:L2', 'B11:E11', 'A12:L12']);
                // 设置单元格内容居中
                $event->sheet->getDelegate()->getStyle('A2:L2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A3:L3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                // 定义列宽度
                $widths = ['A' => 10, 'B' => 15, 'C' => 10, 'D' => 12, 'E' => 14, 'F' => 14, 'G' => 20, 'H' => 25, 'I' => 16, 'J' => 20, 'K' => 10, 'L' => 20];
                foreach ($widths as $k => $v) {
                    // 设置列宽度
                    $event->sheet->getDelegate()->getColumnDimension($k)->setWidth($v);
                }
                //设置行高，$i为数据行数
                for ($i = 0; $i <= $this->rowNum; $i++) {
                    $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(25);
                }
                // 其他样式需求（设置边框，背景色等）处理扩展中给出的宏，也可以自定义宏来实现，详情见官网说明
                $event->sheet->getDelegate()->getStyle('A3:I3')->applyFromArray([
                    // 设置单元格字体
                    'font' => [
                        'name' => '宋体',
                        'bold' => false,
                        'italic' => false,
                        'strikethrough' => false,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                    // 设置单元格背景色
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'rotation' => 0, //渐变角度
                        'startColor' => [
                            'rgb' => 'FEFF00' //初始颜色
                        ],
                        'endColor' => [
                            'argb' => 'FEFF00',
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('J3:K3')->applyFromArray([
                    'font' => [
                        'name' => '宋体',
                        'bold' => false,
                        'italic' => false,
                        'strikethrough' => false,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'rotation' => 0,
                        'startColor' => [
                            'rgb' => 'FF0000'
                        ],
                        'endColor' => [
                            'argb' => 'FF0000',
                        ],
                    ],
                ]);
                // 设置表中字体
                $event->sheet->getDelegate()->getStyle('A1:L' . ($this->rowNum + 1))->applyFromArray([
                    'font' => [
                        'name' => '宋体',
                        'bold' => false,
                        'italic' => false,
                        'strikethrough' => false,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                ]);
                // 给某个区域单元格设置边框
                $event->sheet->getDelegate()->getStyle('A2:L' . ($this->rowNum - 6))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->getStyle('A2')->applyFromArray([
                    'font' => [
                        'name' => '宋体',
                        'bold' => true,
                        'size' => 16,
                        'underline' => true,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                ]);

            },
        ];
    }

    /**
     * collection.
     *
     * @return \Illuminate\Support\Collection
     *
     * @throws \Exception
     */
    public function collection()
    {
        $data = [];
        if (!empty($this->data)) {
            foreach ($this->data as $key => $vo) {
                // 对应的业务和数据处理，此处省略
                // ...
                // 对于长数字字符串导出excel会变成科学计数，请在字符串前面加上 "\t"，例如：$str = "\t" . $str;
            }
        }
        $headings = [
            '编号',
            '备注',
            // ...
        ];
        array_unshift($data, $headings);
        $this->rowNum = count($data);

        // 此处数据需要数组转集合
        return collect($data);
    }

    /**
     * 设置列单元格格式
     */
    public function columnFormats(): array
    {
        // TODO: Implement columnFormats() method.
        return [
            'F' => NumberFormat::FORMAT_NUMBER_00, //金额保留两位小数
            'K' => NumberFormat::FORMAT_NUMBER_00, //金额保留两位小数
        ];
    }
}