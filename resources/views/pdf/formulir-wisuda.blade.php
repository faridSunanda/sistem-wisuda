<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Wisuda - {{ $biodata->user->name_lengkap ?? 'Mahasiswa' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cambria', serif;
            font-size: 12pt;
            color: #000000;
            line-height: 1.4;
            padding: 20px 30px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
            position: relative;
            min-height: 90px;
        }

        .logo-container {
            position: absolute;
            left: 0;
            top: 15px;
        }

        .logo {
            width: 70px;
            height: 70px;
        }

        .univ-info {
            display: inline-block;
            text-align: center;
            margin-top: 10px;
        }

        .univ-info h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .univ-info .panitia {
            font-size: 14pt;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .univ-info .alamat {
            font-size: 10pt;
            color: #555;
        }

        .title {
            text-align: center;
            margin: 25px 0;
        }

        .title h2 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-bottom: 20px;
            font-size: 11pt;
        }

        .data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }

        .data-table .no-col {
            width: 30px;
            text-align: center;
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .data-table .label-col {
            width: 200px;
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .ttd-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #000;
        }

        .ttd-wrapper {
            display: table;
            width: 100%;
        }

        .ttd-left {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: left;
        }

        .ttd-center {
            display: table-cell;
            width: 20%;
            vertical-align: top;
            text-align: right;
        }

        .ttd-right {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: left;
            padding-left: 30px;
        }

        .foto-container {
            text-align: right;
            margin-top: 10px;
            margin-right: -10px;
        }

        .foto-box {
            width: 100px;
            height: 135px;
            border: 1px solid #666;
            background-color: #f9f9f9;
            display: inline-block;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .foto-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .foto-placeholder {
            font-size: 9pt;
            color: #666;
            text-align: right;
            padding: 10px;
            line-height: 1.3;
        }

        .ttd-line {
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 60px;
            font-weight: normal;
            width: 200px;
            text-align: left;
        }

        .ttd-info {
            margin-top: 5px;
            font-size: 10pt;
            color: #000;
            font-weight: normal;
        }

        .divider {
            border-bottom: 1px solid #ccc;
            margin: 15px 0;
        }

        .ttd-content {
            min-height: 150px;
        }

        .ttd-text {
            font-weight: normal;
            text-align: left;
        }

        .mahasiswa-info {
            text-align: left;
            font-weight: normal;
        }
    </style>
</head>

<body>
    <!-- KOP SURAT -->
    <div class="header">
        <div class="logo-container">
            <img src="{{ public_path('img/unwahas_putih.jpg') }}" alt="Logo UNWAHAS" class="logo">
        </div>

        <div class="univ-info">
            <h1>UNIVERSITAS WAHID HASYIM</h1>
            <div class="panitia">PANITIA WISUDA KE 44 TAHUN 2025</div>
            <div class="alamat">Jl. Menoreh Tengah X/22 Sampangan Semarang. Telp. 024-8505680/81</div>
        </div>
    </div>

    <!-- JUDUL FORMULIR -->
    <div class="title">
        <h2>FORMULIR PENDAFTARAN WISUDA</h2>
    </div>

    <!-- DATA PRIBADI -->
    <table class="data-table">
        <tbody>
            @php
                $dataPribadi = [
                    ['Nama Lengkap', $biodata->user->name_lengkap ?? '-'],
                    [
                        'Tempat, Tgl Lahir',
                        ($biodata->tempat_lahir ?? '-') .
                        ', ' .
                        ($biodata->tanggal_lahir
                            ? \Carbon\Carbon::parse($biodata->tanggal_lahir)->format('d-m-Y')
                            : '-'),
                    ],
                    ['NIK', $biodata->nik ?? '-'],
                    ['NIM', $biodata->nim ?? '-'],
                    ['NIRM', $biodata->nirm ?? '-'],
                    ['NIRL', $biodata->nirl ?? '-'],
                    ['Jenis Kelamin', $biodata->jenis_kelamin ?? '-'],
                    ['Status Mahasiswa', $biodata->status_mahasiswa ?? '-'],
                    ['Tahun Masuk', $biodata->tahun_masuk ?? '-'],
                    ['Fakultas', $biodata->fakultas ?? '-'],
                    ['Program Studi', $biodata->program_studi ?? '-'],
                    ['Alamat Rumah', $biodata->alamat_rumah ?? '-'],
                    ['Alamat Kantor', $biodata->alamat_kantor ?? '-'],
                    ['Nomor Telp/HP', $biodata->no_telepon ?? '-'],
                    ['Email', $biodata->user->email ?? '-'],
                ];
            @endphp

            @foreach ($dataPribadi as $index => $item)
                <tr>
                    <td class="no-col">{{ $index + 1 }}.</td>
                    <td class="label-col">{{ $item[0] }}</td>
                    <td>{{ $item[1] }}</td>
                </tr>
            @endforeach

            <!-- DOSEN PEMBIMBING -->
            <tr>
                <td class="no-col">16.</td>
                <td class="label-col">Dosen Pembimbing</td>
                <td>
                    @php $i = 1; @endphp
                    @if ($biodata->dosenPembimbings && $biodata->dosenPembimbings->count() > 0)
                        @foreach ($biodata->dosenPembimbings as $dosen)
                            <div>{{ $i++ }}. {{ $dosen->nama ?? '-' }}</div>
                        @endforeach
                    @else
                        <div>1. -</div>
                        <div>2. -</div>
                    @endif
                </td>
            </tr>

            <!-- JUDUL SKRIPSI -->
            <tr>
                <td class="no-col">17.</td>
                <td class="label-col">Judul Skripsi/Tesis/TA</td>
                <td>{{ $biodata->judul_skripsi ?? '-' }}</td>
            </tr>

            <!-- KESAN PESAN -->
            <tr>
                <td class="no-col">18.</td>
                <td class="label-col">Kesan dan Pesan</td>
                <td>{{ $biodata->kesan_pesan ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="divider"></div>

    <!-- SERTIFIKAT -->
    @php
        $sertifikatConfig = [
            'Sertifikat Kompetensi' => [
                'data' => $biodata->sertifikatKompetensi,
                'kolom' => ['No.', 'Nama Sertifikat', 'Nama dalam Inggris', 'Dikeluarkan Oleh', 'Tanggal'],
            ],
            'Sertifikat Bahasa Internasional' => [
                'data' => $biodata->sertifikatBahasaInternasional,
                'kolom' => ['No.', 'Nama Sertifikat', 'Nama dalam Inggris', 'Dikeluarkan Oleh', 'Tanggal'],
            ],
            'Sertifikat Magang/Kerja Praktek/PPL' => [
                'data' => $biodata->sertifikatMagang,
                'kolom' => ['No.', 'Nama Sertifikat', 'Nama dalam Inggris', 'Dikeluarkan Oleh'],
            ],
            'Sertifikat Pendidikan Karakter' => [
                'data' => $biodata->sertifikatPendidikanKarakter,
                'kolom' => ['No.', 'Nama Sertifikat', 'Nama dalam Inggris', 'Dikeluarkan Oleh', 'Tanggal'],
            ],
            'Sertifikat Penghargaan' => [
                'data' => $biodata->sertifikatPenghargaan,
                'kolom' => ['No.', 'Nama Sertifikat', 'Nama dalam Inggris', 'Dikeluarkan Oleh', 'Tanggal'],
            ],
            'Sertifikat Organisasi' => [
                'data' => $biodata->sertifikatOrganisasi,
                'kolom' => ['No.', 'Nama Sertifikat', 'Nama dalam Inggris', 'Tanggal Mulai', 'Tanggal Selesai'],
            ],
        ];
    @endphp

    @foreach ($sertifikatConfig as $title => $config)
        @if ($config['data'] && $config['data']->count() > 0)
            <div style="font-weight: bold; margin-top: 15px; text-decoration: underline; font-size: 11pt;">
                {{ $title }}:
            </div>
            <table
                style="width: 100%; border-collapse: collapse; border: 1px solid #000; margin: 10px 0 20px 0; font-size: 10pt;">
                <thead>
                    <tr>
                        @foreach ($config['kolom'] as $kolom)
                            <th
                                style="border: 1px solid #000; padding: 5px 6px; background-color: #f0f0f0; font-weight: bold;">
                                {{ $kolom }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($config['data'] as $index => $sertifikat)
                        <tr>
                            <td style="border: 1px solid #000; padding: 5px 6px; text-align: center; width: 30px;">
                                {{ $index + 1 }}</td>
                            <td style="border: 1px solid #000; padding: 5px 6px;">{{ $sertifikat->nama_sertifikat }}
                            </td>
                            <td style="border: 1px solid #000; padding: 5px 6px;">
                                @php
                                    if (!empty($sertifikat->nama_inggris)) {
                                        $englishName = $sertifikat->nama_inggris;
                                    } else {
                                        try {
                                            $translator = new \Stichoza\GoogleTranslate\GoogleTranslate('en', 'id');
                                            $englishName = $translator->translate($sertifikat->nama_sertifikat);
                                            $englishName = ucwords(strtolower($englishName));
                                        } catch (\Exception $e) {
                                            $englishName = $sertifikat->nama_sertifikat;
                                        }
                                    }
                                @endphp
                                {{ $englishName }}
                            </td>

                            @if (in_array($title, [
                                    'Sertifikat Kompetensi',
                                    'Sertifikat Bahasa Internasional',
                                    'Sertifikat Pendidikan Karakter',
                                    'Sertifikat Penghargaan',
                                ]))
                                <td style="border: 1px solid #000; padding: 5px 6px;">{{ $sertifikat->penerbit }}</td>
                                <td style="border: 1px solid #000; padding: 5px 6px; text-align: center;">
                                    {{ $sertifikat->tanggal_terbit ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('d-m-Y') : '-' }}
                                </td>
                            @elseif($title === 'Sertifikat Magang/Kerja Praktek/PPL')
                                <td style="border: 1px solid #000; padding: 5px 6px;">{{ $sertifikat->penerbit }}</td>
                            @elseif($title === 'Sertifikat Organisasi')
                                <td style="border: 1px solid #000; padding: 5px 6px; text-align: center;">
                                    {{ $sertifikat->tanggal_mulai ? \Carbon\Carbon::parse($sertifikat->tanggal_mulai)->format('d-m-Y') : '-' }}
                                </td>
                                <td style="border: 1px solid #000; padding: 5px 6px; text-align: center;">
                                    {{ $sertifikat->tanggal_selesai ? \Carbon\Carbon::parse($sertifikat->tanggal_selesai)->format('d-m-Y') : '-' }}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach

    <div class="divider"></div>

    <!-- VALIDASI SERTIFIKAT -->
    <div style="font-weight: bold; margin-top: 15px; text-decoration: underline; font-size: 11pt;">
        Ceklist Ke-validan Sertifikat (Diisi Kaprodi/TU/Delegasi {{ $biodata->fakultas ?? 'Fakultas' }})
    </div>
    <table
        style="width: 100%; border-collapse: collapse; border: 1px solid #000; margin: 10px 0 20px 0; font-size: 10pt;">
        <thead>
            <tr>
                <th
                    style="border: 1px solid #000; padding: 5px 6px; background-color: #f0f0f0; font-weight: bold; width: 30px; text-align: center;">
                    No.</th>
                <th style="border: 1px solid #000; padding: 5px 6px; background-color: #f0f0f0; font-weight: bold;">
                    Jenis Sertifikat</th>
                <th
                    style="border: 1px solid #000; padding: 5px 6px; background-color: #f0f0f0; font-weight: bold; width: 60px;">
                    Cek</th>
            </tr>
        </thead>
        <tbody>
            @foreach (['Sertifikat Kompetensi', 'Sertifikat Bahasa Internasional', 'Sertifikat Magang/KP/PPL', 'Sertifikat Pendidikan Karakter', 'Sertifikat Penghargaan', 'Sertifikat Organisasi'] as $index => $jenis)
                <tr>
                    <td style="border: 1px solid #000; padding: 5px 6px; text-align: center;">{{ $index + 1 }}.</td>
                    <td style="border: 1px solid #000; padding: 5px 6px;">{{ $jenis }}</td>
                    <td style="border: 1px solid #000; padding: 5px 6px; text-align: center;"></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <div class="ttd-section">
        <div class="ttd-wrapper">
            <div class="ttd-left">
                <div class="ttd-content">
                    <p class="ttd-text">Kajur/Kaprodi/Delegasi</p>
                    <p class="ttd-text">Fakultas {{ $biodata->fakultas ?? 'Fakultas' }}</p>

                    <div class="ttd-line">(................................)</div>
                    <p class="ttd-info">Tanda Tangan dan Stempel</p>
                </div>
            </div>

            <div class="ttd-center">
                <div class="foto-container">
                    <div class="foto-box">
                        @if ($biodata->foto_profile && file_exists(public_path('storage/' . $biodata->foto_profile)))
                            <img src="{{ public_path('storage/' . $biodata->foto_profile) }}" alt="Foto Profil">
                        @else
                            <div class="foto-placeholder">
                                Pas Foto 4x6<br>(Latar Merah)
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="ttd-right">
                <div class="ttd-content">
                    <div class="mahasiswa-info">
                        <p>Semarang, {{ date('d F Y') }}</p>
                        <p>Mahasiswa (Calon wisudawan),</p>
                    </div>

                    <div class="ttd-line">{{ $biodata->user->name_lengkap ?? '-' }}</div>
                    <p class="ttd-info">NIM. {{ $biodata->nim ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
