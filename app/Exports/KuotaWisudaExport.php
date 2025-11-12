<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class KuotaWisudaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithColumnWidths, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $collection = collect($this->data);
        
        return $collection->values()->map(function ($row, $index) {
            if (is_object($row)) {
                $row->export_index = $index + 1;
            } elseif (is_array($row)) {
                $row['export_index'] = $index + 1;
            }
            return $row;
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Tahun Wisuda',
            'Jumlah Kuota',
            'Status Periode'
        ];
    }

    public function map($row): array
    {
        $no = is_object($row) ? ($row->export_index ?? 0) : ($row['export_index'] ?? 0);
        $tahun_wisuda = is_object($row) ? ($row->tahun_wisuda ?? '') : ($row['tahun_wisuda'] ?? '');
        $jumlah_kuota = is_object($row) ? ($row->jumlah_kuota ?? 0) : ($row['jumlah_kuota'] ?? 0);
        $status = is_object($row) ? ($row->status_periode ?? '') : ($row['status_periode'] ?? '');
        
        return [
            $no,
            $tahun_wisuda,
            number_format($jumlah_kuota, 0, ',', '.') . ' orang',
            $status
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 15,
            'C' => 20,
            'D' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            3 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '3B82F6'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                $this->addTitleAndDate($sheet);
                $this->applyBorders($sheet);
                $this->applyAlignment($sheet);
                $this->applySheetSettings($sheet);
            },
        ];
    }

    protected function addTitleAndDate(Worksheet $sheet): void
    {
        $sheet->insertNewRowBefore(1, 2);
        
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'Laporan Kuota Wisuda');
        
        $sheet->mergeCells('A2:D2');
        $sheet->setCellValue('A2', 'Tanggal: ' . $this->getFormattedDate());

        $titleStyle = [
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '000000']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        $dateStyle = [
            'font' => ['size' => 11, 'color' => ['rgb' => '000000']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        $sheet->getStyle('A1')->applyFromArray($titleStyle);
        $sheet->getStyle('A2')->applyFromArray($dateStyle);
        
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(2)->setRowHeight(20);
    }

    protected function getFormattedDate(): string
    {
        $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        return date('d') . ' ' . $bulan[(int)date('n')] . ' ' . date('Y');
    }

    protected function applyBorders(Worksheet $sheet): void
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $headerBorder = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $dataBorder = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $headerAlignment = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        $headerRange = 'A3:' . $highestColumn . '3';
        $sheet->getStyle($headerRange)->applyFromArray($headerBorder);
        $sheet->getStyle($headerRange)->applyFromArray($headerAlignment);

        if ($highestRow > 3) {
            $sheet->getStyle('A4:' . $highestColumn . $highestRow)->applyFromArray($dataBorder);
        }
    }

    protected function applyAlignment(Worksheet $sheet): void
    {
        $highestRow = $sheet->getHighestRow();
        
        if ($highestRow <= 3) {
            return;
        }

        $dataStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFFFF'],
            ],
            'font' => [
                'bold' => false,
                'size' => 11,
                'color' => ['rgb' => '000000'],
            ],
        ];

        $centerAlignment = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        $dataRange = 'A4:' . $sheet->getHighestColumn() . $highestRow;
        $sheet->getStyle($dataRange)->applyFromArray($dataStyle);

        $sheet->getStyle('A4:D' . $highestRow)->applyFromArray($centerAlignment);
    }

    protected function applySheetSettings(Worksheet $sheet): void
    {
        $highestColumn = $sheet->getHighestColumn();
        
        $sheet->getRowDimension(3)->setRowHeight(25);
        $sheet->freezePane('A4');
        $sheet->setAutoFilter('A3:' . $highestColumn . '3');
    }

    public function title(): string
    {
        return 'Kuota Wisuda';
    }
}

