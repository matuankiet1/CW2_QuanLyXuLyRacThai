<?php

namespace App\Exports;

use App\Models\CollectionSchedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class CollectionScheduleExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnWidths
{
    protected ?string $q;

    public function __construct(?string $q = null)
    {
        $this->q = $q;
    }

    // Lấy dữ liệu
    public function collection()
    {
        $query = CollectionSchedule::query()->with('staff');
        if (!empty($this->q)) {
            $keyword = $this->q;

            $query->where(function ($q) use ($keyword) {
                
                $q->where('status', 'like', "%{$keyword}%")
                    ->orWhere('scheduled_date', 'like', "%{$keyword}%");
                // Tìm theo tên nhân viên
                $q->orWhereHas('staff', function ($sub) use ($keyword) {
                    $sub->where('name', 'like', "%{$keyword}%");
                });
            });
        }
        else{
            $query->orderBy('collection_schedules.schedule_id', 'asc');
        }
        
        return $query->select('collection_schedules.*')->get();
    }

    // Định nghĩa header dòng đầu tiên
    public function headings(): array
    {
        return [
            '#',
            'Tên nhân viên thực hiện',
            'Ngày thu gom',
            'Ngày hoàn thành',
            'Trạng thái',
            'Ngày tạo',
        ];
    }

    // Map từng dòng dữ liệu ra từng cột
    public function map($collection): array
    {
        return [
            $collection->schedule_id,
            $collection->staff?->name ?? $collection->staff_id,
            $collection->scheduled_date,
            $collection->completed_at,
            $collection->status,
            $collection->created_at?->format('Y-m-d H:i:s'),
        ];
    }

    // Format giao diện Excel
    public function styles(Worksheet $sheet)
    {
        // Style cho header (row 1)
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 13,
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center'
            ],
            'fill' => [
                'fillType' => 'solid',
                'color' => ['rgb' => 'D9E1F2'] // xanh nhạt đẹp
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Style toàn bộ dữ liệu (cả sheet)
        $sheet->getStyle('A2:F' . ($sheet->getHighestRow()))->applyFromArray([
            'alignment' => [
                'vertical' => 'center',
                'horizontal' => 'left'
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Đổi font toàn bộ file
        $sheet->getStyle('A1:F' . $sheet->getHighestRow())
            ->getFont()
            ->setName('Times New Roman')
            ->setSize(13);

        // Tăng chiều cao dòng
        $sheet->getDefaultRowDimension()->setRowHeight(25);

        // Tăng chiều cao dòng Header
        $sheet->getRowDimension(1)->setRowHeight(28);

        $highestRow = $sheet->getHighestRow();

        for ($row = 2; $row <= $highestRow; $row++) {
            $cellValue = $sheet->getCell("E{$row}")->getValue();

            if ($cellValue === 'Đã hoàn thành') {
                $sheet->getStyle("E{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => 'solid',
                        'color' => ['rgb' => 'C6EFCE'] // xanh lá nhạt
                    ],
                    'font' => [
                        'color' => ['rgb' => '006100'] // xanh đậm
                    ]
                ]);
            }
        }

        return [];
    }

    // Độ rộng cột
    public function columnWidths(): array
    {
        return [
            'A' => 7,   // #
            'B' => 30,  // Tên nhân viên
            'C' => 25,  // Ngày thu gom
            'D' => 25,  // Ngày hoàn thành
            'E' => 20,  // Trạng thái
            'F' => 25,  // Ngày tạo
        ];
    }
}
