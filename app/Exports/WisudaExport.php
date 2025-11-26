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

class WisudaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithColumnWidths, WithEvents
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
            'Angkatan',
            'Tanggal Pendaftaran',
            'Tanggal Penutupan',
            'Kuota Wisudawan',
            'Status'
        ];
    }

    public function map($row): array
    {
        $no = is_object($row) ? ($row->export_index ?? 0) : ($row['export_index'] ?? 0);
        $angkatan = is_object($row) ? ($row->angkatan ?? '-') : ($row['angkatan'] ?? '-');

        $tanggal_pendaftaran = '';
        $rawTglDaftar = is_object($row) ? ($row->tanggal_pendaftaran ?? null) : ($row['tanggal_pendaftaran'] ?? null);
        if ($rawTglDaftar) {
            $tanggal_pendaftaran = Carbon::parse($rawTglDaftar)->format('d F Y H:i') . ' WIB';
        }

        $tanggal_penutupan = '';
        $rawTglTutup = is_object($row) ? ($row->tanggal_penutupan ?? null) : ($row['tanggal_penutupan'] ?? null);
        if ($rawTglTutup) {
            $tanggal_penutupan = Carbon::parse($rawTglTutup)->format('d F Y H:i') . ' WIB';
        }

        $kuota_val = is_object($row) ? ($row->kuota_wisudawan ?? 0) : ($row['kuota_wisudawan'] ?? 0);
        $kuota_wisudawan = number_format((float)$kuota_val, 0, ',', '.');

        $rawStatus = is_object($row) ? ($row->status ?? '') : ($row['status'] ?? '');
        $statusText = ucfirst($rawStatus);

        return [
            $no,
            $angkatan,
            $tanggal_pendaftaran,
            $tanggal_penutupan,
            $kuota_wisudawan,
            $statusText
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,  // No
            'B' => 20, // Angkatan
            'C' => 30, // Tanggal Pendaftaran
            'D' => 30, // Tanggal Penutupan
            'E' => 20, // Kuota Wisudawan
            'F' => 15, // Status
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
                    'startColor' => ['rgb' => '3B82F6'], // Biru
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

        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'Laporan Data Wisuda');

        $sheet->mergeCells('A2:F2');
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
        $highestColumn = 'F';

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

        $dataRange = 'A4:F' . $highestRow;
        $sheet->getStyle($dataRange)->applyFromArray($dataStyle);

        $sheet->getStyle('A4:A' . $highestRow)->applyFromArray($centerAlignment);
        $sheet->getStyle('E4:F' . $highestRow)->applyFromArray($centerAlignment);

        $sheet->getStyle('B4:D' . $highestRow)->applyFromArray($leftAlignment);
    }

    protected function applySheetSettings(Worksheet $sheet): void
    {
        $highestColumn = 'F';

        $sheet->getRowDimension(3)->setRowHeight(25);
        $sheet->freezePane('A4');
        $sheet->setAutoFilter('A3:' . $highestColumn . '3');
    }

    public function title(): string
    {
        return 'Data Wisuda';
    }
}
