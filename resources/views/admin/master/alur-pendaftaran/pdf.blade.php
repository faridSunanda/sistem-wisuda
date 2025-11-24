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
            /* Warna biru header */
            color: white;
            font-weight: bold;
            text-align: center;
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
                <th width="5%">No</th>
                <th width="10%">Urutan</th>
                <th width="25%">Judul</th>
                <th width="60%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $row->no_urut }}</td>
                    <td>{{ $row->judul }}</td>
                    <td>{{ $row->keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
