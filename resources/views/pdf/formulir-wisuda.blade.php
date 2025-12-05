<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Wisuda - {{ $biodata->user->name_lengkap ?? '' }}</title>
    <style>
        /* Reset default margin dan padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Cambria', serif;
            font-size: 12pt;
            line-height: 1.4;
            color: #000;
            padding: 20px;
        }
        
        /* Header */
        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 10px;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            flex-shrink: 0;
        }
        
        .header-center {
            text-align: center;
            flex-grow: 1;
            margin: 0 15px;
        }
        
        .university-name {
            font-weight: bold;
            font-size: 16pt;
            line-height: 1.2;
        }
        
        .committee {
            font-weight: bold;
            font-size: 14pt;
            line-height: 1.2;
            margin-top: 2px;
        }
        
        .address {
            font-size: 10pt;
            color: #666;
            margin-top: 3px;
        }
        
        /* Title */
        .form-title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            text-decoration: underline;
            margin: 25px 0;
            text-transform: uppercase;
        }
        
        /* Biodata Grid */
        .biodata-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 2px 5px;
            margin-bottom: 20px;
        }
        
        .biodata-label {
            font-weight: bold;
            white-space: nowrap;
        }
        
        .biodata-value {
            margin-left: 5px;
        }
        
        /* Tables */
        .certificate-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 10pt;
        }
        
        .certificate-table th,
        .certificate-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: left;
            vertical-align: top;
        }
        
        .certificate-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        
        .certificate-table td {
            word-wrap: break-word;
        }
        
        .table-number {
            width: 30px;
            text-align: center;
        }
        
        .table-date {
            width: 80px;
            text-align: center;
        }
        
        /* Section Titles */
        .section-title {
            font-weight: bold;
            text-decoration: underline;
            margin: 15px 0 8px 0;
            font-size: 11pt;
        }
        
        /* Footer */
        .footer-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .left-signature {
            width: 35%;
        }
        
        .right-content {
            width: 60%;
            display: flex;
            justify-content: space-between;
        }
        
        .photo-container {
            flex-shrink: 0;
            width: 110px;
            margin-right: 20px;
        }
        
        .photo-box {
            width: 110px;
            height: 150px;
            border: 1px solid #666;
            background-color: #f8f8f8;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .photo-label {
            font-size: 9pt;
            color: #666;
            text-align: center;
            line-height: 1.2;
        }
        
        .student-signature {
            text-align: left;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 50px;
            width: 200px;
        }
        
        .signature-label {
            font-size: 9pt;
            color: #666;
            margin-top: 2px;
        }
        
        /* No Data */
        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            margin: 10px 0;
        }
        
        /* Page Break */
        .page-break {
            page-break-before: always;
        }
        
        /* Utilities */
        .text-center {
            text-align: center;
        }
        
        .mt-10 {
            margin-top: 10px;
        }
        
        .mt-20 {
            margin-top: 20px;
        }
        
        .mt-30 {
            margin-top: 30px;
        }
        
        .mb-10 {
            margin-bottom: 10px;
        }
        
        .mb-20 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Header dengan logo -->
    <div class="header-container">
        <img src="{{ public_path('img/unwahas_putih.jpg') }}" alt="Logo UNWAHAS" class="logo">
        
        <div class="header-center">
            <div class="university-name">UNIVERSITAS WAHID HASYIM</div>
            <div class="committee">PANITIA WISUDA KE 44 TAHUN 2025</div>
            <div class="address">Jl. Menoreh Tengah X/22 Sampangan Semarang. Telp. 024-8505680/81</div>
        </div>
        
        <div style="width: 80px;"></div> <!-- Spacer untuk alignment -->
    </div>
    
    <!-- Judul Formulir -->
    <div class="form-title">FORMULIR PENDAFTARAN WISUDA</div>
    
    <!-- Data Pribadi -->
    <div class="biodata-grid">
        <div class="biodata-label">1. Nama Lengkap</div>
        <div class="biodata-value">: {{ $biodata->user->name_lengkap ?? '-' }}</div>
        
        <div class="biodata-label">2. Tempat, Tgl Lahir</div>
        <div class="biodata-value">: {{ $biodata->tempat_lahir ?? '-' }}, 
            {{ $biodata->tanggal_lahir ? \Carbon\Carbon::parse($biodata->tanggal_lahir)->format('d-m-Y') : '-' }}
        </div>
        
        <div class="biodata-label">3. NIK</div>
        <div class="biodata-value">: {{ $biodata->nik ?? '-' }}</div>
        
        <div class="biodata-label">4. NIM</div>
        <div class="biodata-value">: {{ $biodata->nim ?? '-' }}</div>
        
        <div class="biodata-label">5. NIRM</div>
        <div class="biodata-value">: {{ $biodata->nirm ?? '-' }}</div>
        
        <div class="biodata-label">6. NIRL</div>
        <div class="biodata-value">: {{ $biodata->nirl ?? '-' }}</div>
        
        <div class="biodata-label">7. Jenis Kelamin</div>
        <div class="biodata-value">: {{ $biodata->jenis_kelamin ?? '-' }}</div>
        
        <div class="biodata-label">8. Status Mahasiswa</div>
        <div class="biodata-value">: {{ $biodata->status_mahasiswa ?? '-' }}</div>
        
        <div class="biodata-label">9. Tahun Masuk</div>
        <div class="biodata-value">: {{ $biodata->tahun_masuk ?? '-' }}</div>
        
        <div class="biodata-label">10. Fakultas</div>
        <div class="biodata-value">: {{ $biodata->fakultas ?? '-' }}</div>
        
        <div class="biodata-label">11. Program Studi</div>
        <div class="biodata-value">: {{ $biodata->program_studi ?? '-' }}</div>
        
        <div class="biodata-label">12. Alamat Rumah</div>
        <div class="biodata-value">: {{ $biodata->alamat_rumah ?? '-' }}</div>
        
        <div class="biodata-label">13. Alamat Kantor</div>
        <div class="biodata-value">: {{ $biodata->alamat_kantor ?? '-' }}</div>
        
        <div class="biodata-label">14. Nomor Telp/HP</div>
        <div class="biodata-value">: {{ $biodata->no_telepon ?? '-' }}</div>
        
        <div class="biodata-label">15. Email</div>
        <div class="biodata-value">: {{ $biodata->email ?? '-' }}</div>
        
        <div class="biodata-label">16. Dosen Pembimbing</div>
        <div class="biodata-value">
            @php $i = 1; @endphp
            @if ($biodata->dosenPembimbings && $biodata->dosenPembimbings->count() > 0)
                @foreach ($biodata->dosenPembimbings as $dosen)
                    <div>
                        @if ($i == 1)
                            : {{ $i }}. {{ $dosen->nama ?? '-' }}
                        @else
                            &nbsp;&nbsp;&nbsp;{{ $i }}. {{ $dosen->nama ?? '-' }}
                        @endif
                    </div>
                    @php $i++; @endphp
                @endforeach
            @else
                : 1. -<br>
                &nbsp;&nbsp;&nbsp;2. -
            @endif
        </div>
        
        <div class="biodata-label">17. Judul Skripsi/Tesis/TA</div>
        <div class="biodata-value">: {{ $biodata->judul_skripsi ?? '-' }}</div>
        
        <div class="biodata-label">18. Kesan dan Pesan</div>
        <div class="biodata-value">: {{ $biodata->kesan_pesan ?? '-' }}</div>
    </div>
    
    <!-- Sertifikat Kompetensi -->
    <div class="section-title">Sertifikat Kompetensi:</div>
    @if($biodata->sertifikatKompetensi && $biodata->sertifikatKompetensi->count() > 0)
        <table class="certificate-table">
            <thead>
                <tr>
                    <th class="table-number">No.</th>
                    <th>Nama Sertifikat</th>
                    <th>Nama dalam Inggris</th>
                    <th>Dikeluarkan Oleh</th>
                    <th class="table-date">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($biodata->sertifikatKompetensi as $sertifikat)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $sertifikat->nama_sertifikat }}</td>
                        <td>{{ $sertifikat->nama_inggris }}</td>
                        <td>{{ $sertifikat->penerbit }}</td>
                        <td class="text-center">{{ $sertifikat->tanggal_terbit ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('d-m-Y') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">Tidak ada data.</div>
    @endif
    
    <!-- Sertifikat Bahasa Internasional -->
    <div class="section-title">Sertifikat Bahasa Internasional:</div>
    @if($biodata->sertifikatBahasaInternasional && $biodata->sertifikatBahasaInternasional->count() > 0)
        <table class="certificate-table">
            <thead>
                <tr>
                    <th class="table-number">No.</th>
                    <th>Nama Sertifikat</th>
                    <th>Nama dalam Inggris</th>
                    <th>Dikeluarkan Oleh</th>
                    <th class="table-date">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($biodata->sertifikatBahasaInternasional as $sertifikat)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $sertifikat->nama_sertifikat }}</td>
                        <td>{{ $sertifikat->nama_inggris }}</td>
                        <td>{{ $sertifikat->penerbit }}</td>
                        <td class="text-center">{{ $sertifikat->tanggal_terbit ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('d-m-Y') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">Tidak ada data.</div>
    @endif
    
    <!-- Sertifikat Magang/Kerja Praktek/PPL -->
    <div class="section-title">Sertifikat Magang/Kerja Praktek/PPL:</div>
    @if($biodata->sertifikatMagang && $biodata->sertifikatMagang->count() > 0)
        <table class="certificate-table">
            <thead>
                <tr>
                    <th class="table-number">No.</th>
                    <th>Nama Sertifikat</th>
                    <th>Nama dalam Inggris</th>
                    <th>Dikeluarkan Oleh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($biodata->sertifikatMagang as $sertifikat)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $sertifikat->nama_sertifikat }}</td>
                        <td>{{ $sertifikat->nama_inggris }}</td>
                        <td>{{ $sertifikat->penerbit }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">Tidak ada data.</div>
    @endif
    
    <!-- Sertifikat Pendidikan Karakter -->
    <div class="section-title">Sertifikat Pendidikan Karakter:</div>
    @if($biodata->sertifikatPendidikanKarakter && $biodata->sertifikatPendidikanKarakter->count() > 0)
        <table class="certificate-table">
            <thead>
                <tr>
                    <th class="table-number">No.</th>
                    <th>Nama Sertifikat</th>
                    <th>Nama dalam Inggris</th>
                    <th>Dikeluarkan Oleh</th>
                    <th class="table-date">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($biodata->sertifikatPendidikanKarakter as $sertifikat)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $sertifikat->nama_sertifikat }}</td>
                        <td>{{ $sertifikat->nama_inggris }}</td>
                        <td>{{ $sertifikat->penerbit }}</td>
                        <td class="text-center">{{ $sertifikat->tanggal_terbit ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('d-m-Y') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">Tidak ada data.</div>
    @endif
    
    <!-- Sertifikat Penghargaan -->
    <div class="section-title">Sertifikat Penghargaan:</div>
    @if($biodata->sertifikatPenghargaan && $biodata->sertifikatPenghargaan->count() > 0)
        <table class="certificate-table">
            <thead>
                <tr>
                    <th class="table-number">No.</th>
                    <th>Nama Sertifikat</th>
                    <th>Nama dalam Inggris</th>
                    <th>Dikeluarkan Oleh</th>
                    <th class="table-date">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($biodata->sertifikatPenghargaan as $sertifikat)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $sertifikat->nama_sertifikat }}</td>
                        <td>{{ $sertifikat->nama_inggris }}</td>
                        <td>{{ $sertifikat->penerbit }}</td>
                        <td class="text-center">{{ $sertifikat->tanggal_terbit ? \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('d-m-Y') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">Tidak ada data.</div>
    @endif
    
    <!-- Sertifikat Organisasi -->
    <div class="section-title">Sertifikat Organisasi:</div>
    @if($biodata->sertifikatOrganisasi && $biodata->sertifikatOrganisasi->count() > 0)
        <table class="certificate-table">
            <thead>
                <tr>
                    <th class="table-number">No.</th>
                    <th>Nama Sertifikat</th>
                    <th>Nama dalam Inggris</th>
                    <th class="table-date">Tanggal Mulai</th>
                    <th class="table-date">Tanggal Selesai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($biodata->sertifikatOrganisasi as $sertifikat)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $sertifikat->nama_sertifikat }}</td>
                        <td>{{ $sertifikat->nama_inggris }}</td>
                        <td class="text-center">{{ $sertifikat->tanggal_mulai ? \Carbon\Carbon::parse($sertifikat->tanggal_mulai)->format('d-m-Y') : '-' }}</td>
                        <td class="text-center">{{ $sertifikat->tanggal_selesai ? \Carbon\Carbon::parse($sertifikat->tanggal_selesai)->format('d-m-Y') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">Tidak ada data.</div>
    @endif
    
    <!-- Checklist Validasi -->
    <div class="section-title mt-20">Ceklist Ke-validan Sertifikat (Diisi Kaprodi/TU/Delegasi Fakultas Teknik)</div>
    <table class="certificate-table">
        <thead>
            <tr>
                <th class="table-number">No.</th>
                <th>Jenis Sertifikat</th>
                <th class="table-date">Cek</th>
            </tr>
        </thead>
        <tbody>
            @for($i = 1; $i <= 6; $i++)
                <tr>
                    <td class="text-center">{{ $i }}.</td>
                    <td>
                        @if($i == 1) Sertifikat Kompetensi
                        @elseif($i == 2) Sertifikat Bahasa Internasional
                        @elseif($i == 3) Sertifikat Magang/KP/PPL
                        @elseif($i == 4) Sertifikat Pendidikan Karakter
                        @elseif($i == 5) Sertifikat Penghargaan
                        @elseif($i == 6) Sertifikat Organisasi
                        @endif
                    </td>
                    <td></td>
                </tr>
            @endfor
        </tbody>
    </table>
    
    <!-- Footer dengan tanda tangan dan foto -->
    <div class="footer-section">
        <!-- Tanda tangan kiri -->
        <div class="left-signature">
            <div>Kajur/Kaprodi/Delegasi Fakultas Teknik</div>
            <div style="height: 80px;"></div>
            <div class="signature-line">(................................)</div>
            <div class="signature-label">Tanda Tangan dan Stempel</div>
        </div>
        
        <!-- Konten kanan: foto dan tanda tangan mahasiswa -->
        <div class="right-content">
            <!-- Foto -->
            <div class="photo-container">
                <div class="photo-box">
                    @if($biodata->foto_profile && file_exists(storage_path('app/public/' . $biodata->foto_profile)))
                        <img src="{{ storage_path('app/public/' . $biodata->foto_profile) }}" 
                             alt="Foto Profil">
                    @else
                        <div class="photo-label">
                            Pas Foto 4x6<br>
                            (Latar Merah)
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Tanda tangan mahasiswa -->
            <div class="student-signature">
                <div>Semarang, {{ date('d F Y') }}</div>
                <div>Mahasiswa (Calon wisudawan),</div>
                <div style="height: 80px;"></div>
                <div class="signature-line">{{ $biodata->user->name_lengkap ?? '-' }}</div>
                <div>NIM. {{ $biodata->nim ?? '-' }}</div>
            </div>
        </div>
    </div>
</body>
</html>