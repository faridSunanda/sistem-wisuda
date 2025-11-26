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
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #3b82f6;
            color: white;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .status-text {
            font-weight: bold;
            text-transform: capitalize;
        }
    </style>
</head>

<body>
    <h1>{{ $title }}</h1>
    <p style="text-align: right; font-size: 9px; margin-bottom: 5px;">
        Dicetak pada: {{ date('d F Y H:i') }}
    </p>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Angkatan</th>
                <th width="25%">Tanggal Pendaftaran</th>
                <th width="25%">Tanggal Penutupan</th>
                <th width="15%">Kuota</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $row->angkatan }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($row->tanggal_pendaftaran)->translatedFormat('d F Y H:i') }} WIB
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($row->tanggal_penutupan)->translatedFormat('d F Y H:i') }} WIB
                    </td>
                    <td class="text-right">
                        {{ number_format($row->kuota_wisudawan, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        <span class="status-text">{{ ucfirst($row->status) }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
