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
                <th class="text-center">Tahun Wisuda</th>
                <th class="text-center">Status</th>
                <th>Waktu Buka</th>
                <th>Waktu Tutup</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $row->tahun_wisuda ?? '' }}</td>
                <td class="text-center">{{ $row->status ?? '' }}</td>
                <td>{{ $row->waktu_buka_pendaftaran ? \Carbon\Carbon::parse($row->waktu_buka_pendaftaran)->format('d F Y H:i') : '' }}</td>
                <td>{{ $row->waktu_tutup_pendaftaran ? \Carbon\Carbon::parse($row->waktu_tutup_pendaftaran)->format('d F Y H:i') : '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

