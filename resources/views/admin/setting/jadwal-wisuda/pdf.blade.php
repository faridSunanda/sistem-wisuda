<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #3b82f6;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Nama Kegiatan</th>
                <th>Waktu Pelaksanaan</th>
                <th>Tempat</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $row->nama_kegiatan ?? '' }}</td>
                <td>{{ $row->waktu_pelaksanaan ? \Carbon\Carbon::parse($row->waktu_pelaksanaan)->format('d F Y H:i') : '' }}</td>
                <td>{{ $row->tempat_pelaksanaan ?? '' }}</td>
                <td>{{ $row->keterangan ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

