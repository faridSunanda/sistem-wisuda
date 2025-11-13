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
                <th>Nama Lengkap</th>
                <th class="text-center">NIM</th>
                <th class="text-center">Tahun Masuk</th>
                <th class="text-center">Jenjang</th>
                <th class="text-center">Fakultas</th>
                <th class="text-center">Prodi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $row->nama ?? '' }}</td>
                <td class="text-center">{{ $row->nim ?? '' }}</td>
                <td class="text-center">{{ $row->tahun_masuk ?? '' }}</td>
                <td class="text-center">{{ $row->jenjang ?? '' }}</td>
                <td class="text-center">{{ $row->fakultas ?? '' }}</td>
                <td class="text-center">{{ $row->prodi ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

