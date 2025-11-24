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

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
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
                <th class="text-center" width="5%">No</th>
                <th width="30%">Nama Dokumen</th>
                <th width="40%">Keterangan</th>
                <th width="25%">Berkas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $row->nama_dokumen ?? '-' }}</td>
                    <td>{{ $row->keterangan ?? '-' }}</td>
                    <td>{{ $row->berkas ? basename($row->berkas) : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
