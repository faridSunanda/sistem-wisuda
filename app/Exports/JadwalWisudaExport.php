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

class JadwalWisudaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithColumnWidths, WithEvents
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
            'Nama Kegiatan',
            'Waktu Pelaksanaan',
            'Tempat',
            'Keterangan'
        ];
    }

    public function map($row): array
    {
        $no = is_object($row) ? ($row->export_index ?? 0) : ($row['export_index'] ?? 0);
        $nama_kegiatan = is_object($row) ? ($row->nama_kegiatan ?? '') : ($row['nama_kegiatan'] ?? '');
        $tempat = is_object($row) ? ($row->tempat_pelaksanaan ?? '') : ($row['tempat_pelaksanaan'] ?? '');
        $keterangan = is_object($row) ? ($row->keterangan ?? '') : ($row['keterangan'] ?? '');
        
        $waktu = '';
        if (is_object($row)) {
            if (isset($row->waktu_pelaksanaan)) {
                $waktu = $row->waktu_pelaksanaan instanceof \DateTime 
                    ? Carbon::parse($row->waktu_pelaksanaan)->format('d F Y H:i')
                    : Carbon::parse($row->waktu_pelaksanaan)->format('d F Y H:i');
            }
        } else {
            $waktu = isset($row['waktu_pelaksanaan']) 
                ? Carbon::parse($row['waktu_pelaksanaan'])->format('d F Y H:i')
                : '';
        }
        
        return [
            $no,
            $nama_kegiatan,
            $waktu,
            $tempat,
            $keterangan
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 30,
            'C' => 25,
            'D' => 25,
            'E' => 40,
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
        
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'Laporan Jadwal Wisuda');
        
        $sheet->mergeCells('A2:E2');
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

        $leftAlignment = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        $dataRange = 'A4:' . $sheet->getHighestColumn() . $highestRow;
        $sheet->getStyle($dataRange)->applyFromArray($dataStyle);

        $sheet->getStyle('A4:A' . $highestRow)->applyFromArray($centerAlignment);
        $sheet->getStyle('B4:B' . $highestRow)->applyFromArray($leftAlignment);
        $sheet->getStyle('C4:C' . $highestRow)->applyFromArray($leftAlignment);
        $sheet->getStyle('D4:D' . $highestRow)->applyFromArray($leftAlignment);
        $sheet->getStyle('E4:E' . $highestRow)->applyFromArray($leftAlignment);
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
        return 'Jadwal Wisuda';
    }
}

