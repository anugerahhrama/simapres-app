<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Monitoring Prestasi Mahasiswa</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .kop {
            text-align: center;
            margin-bottom: 0;
        }
        .kop .instansi1 { font-size: 14px; font-weight: bold; text-transform: uppercase; }
        .kop .instansi2 { font-size: 16px; font-weight: bold; text-transform: uppercase; }
        .kop .instansi3 { font-size: 15px; font-weight: bold; text-transform: uppercase; }
        .kop .alamat { font-size: 11px; }
        .kop .web { font-size: 11px; }
        .garis2 { border-bottom: 3px double #000; margin-top: 2px; margin-bottom: 18px; }
        .judul { font-size: 15px; font-weight: bold; margin-bottom: 18px; text-align: center; }
        .detail-table { margin: 0 auto 20px auto; width: 60%; }
        .detail-table td { padding: 4px 8px; }
        .info-vertical { width: 100%; margin-bottom: 20px; }
        .info-vertical td { border: 1px solid #ccc; padding: 10px 15px; text-align: center; font-size: 15px; font-weight: bold; }
        .section-title { margin: 18px 0 8px 0; font-weight: bold; font-size: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f2f2f2; }
        .ttd { width: 250px; float: right; text-align: center; margin-top: 40px; }
        @page { margin: 30px 30px 60px 30px; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: right; font-size: 11px; }
    </style>
</head>
<body>
    <div class="kop">
        <div class="instansi1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</div>
        <div class="instansi2">POLITEKNIK NEGERI MALANG</div>
        <div class="instansi3">JURUSAN TEKNOLOGI INFORMASI</div>
        <div class="alamat">Jl. Soekarno Hatta No. 9, Jatimulyo, Lowokwaru, Malang 65141</div>
        <div class="alamat">Telp. (0341) 404424 – 404425, Fax (0341) 404420</div>
        <div class="web">https://jti.polinema.ac.id</div>
        <div class="garis2"></div>
    </div>

    <div class="judul">LAPORAN MONITORING PRESTASI MAHASISWA</div>

    <table class="detail-table" style="margin-bottom: 10px;">
        <tr>
            <td width="120">Nama</td>
            <td width="10">:</td>
            <td>{{ $detailUser->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>NIM</td>
            <td>:</td>
            <td>{{ $detailUser->no_induk ?? '-' }}</td>
        </tr>
        <tr>
            <td>Prodi</td>
            <td>:</td>
            <td>{{ $detailUser->prodi->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>No. HP</td>
            <td>:</td>
            <td>{{ $detailUser->phone ?? '-' }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>:</td>
            <td>{{ $detailUser->user->email ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>
                @if(isset($detailUser->jenis_kelamin))
                    {{ $detailUser->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                @else
                    -
                @endif
            </td>
        </tr>
    </table>

    <table class="info-vertical">
        <tr>
            <td>Lomba Aktif<br><span style="font-size:22px;">{{ $totalLombaAktif ?? 0 }}</span></td>
            <td>Dokumen Terunggah<br><span style="font-size:22px;">{{ $dokumenDiunggah->count() ?? 0 }}</span></td>
            <td>Sertifikat<br><span style="font-size:22px;">{{ $totalSertifikat ?? 0 }}</span></td>
            <td>Evaluasi<br><span style="font-size:22px;">{{ $totalEvaluasi ?? 0 }}</span></td>
        </tr>
    </table>

    <div class="section-title">A. Daftar Prestasi yang Telah Diinput</div>
    <table>
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th>Nama Lomba</th>
                <th>Penyelenggara</th>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Pencapaian</th>
            </tr>
        </thead>
        <tbody>
            @php
                $daftarPrestasi = \App\Models\Prestasi::where('mahasiswa_id', $detailUser->user_id ?? null)->orderBy('tanggal', 'desc')->get();
            @endphp
            @forelse($daftarPrestasi as $i => $prestasi)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $prestasi->nama_lomba }}</td>
                    <td>{{ $prestasi->penyelenggara }}</td>
                    <td>{{ $prestasi->tanggal ? \Carbon\Carbon::parse($prestasi->tanggal)->format('d M Y') : '-' }}</td>
                    <td>{{ $prestasi->kategori }}</td>
                    <td>{{ $prestasi->pencapaian }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Belum ada prestasi yang diinput.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">B. Lomba yang Diikuti</div>
    <table>
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th>Judul Lomba</th>
                <th>Status</th>
                <th>Progress</th>
                <th>Label</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pendaftaranLombas ?? [] as $index => $pendaftaran)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pendaftaran->lomba->judul ?? 'N/A' }}</td>
                    <td>{{ $pendaftaran->status ?? '-' }}</td>
                    <td>{{ $pendaftaran->progress ?? 0 }}%</td>
                    <td>{{ $pendaftaran->label ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;">Belum ada lomba yang diikuti.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">C. Dokumen yang Diunggah</div>
    <table>
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th>Jenis Dokumen</th>
                <th>Tanggal Diunggah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dokumenDiunggah ?? [] as $index => $dokumen)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $dokumen->jenis_dokumen ?? '-' }}</td>
                    <td>
                        {{ isset($dokumen->tanggal_upload) ? \Carbon\Carbon::parse($dokumen->tanggal_upload)->format('d M Y') : '-' }}
                    </td>
                    <td>
                        @if (($dokumen->status_verifikasi ?? '') == 'Disetujui')
                            Disetujui
                        @elseif (($dokumen->status_verifikasi ?? '') == 'Menunggu')
                            Menunggu
                        @else
                            {{ $dokumen->status_verifikasi ?? '-' }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;">Belum ada dokumen yang diunggah.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">D. Sertifikat Prestasi</div>
    <table>
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th>Nama Lomba</th>
                <th>Jenis Dokumen</th>
                <th>Tanggal Upload</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @forelse ($dokumenDiunggah->where('jenis_dokumen', 'Sertifikat') as $sertifikat)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $sertifikat->prestasi->nama_lomba ?? '-' }}</td>
                    <td>{{ $sertifikat->jenis_dokumen }}</td>
                    <td>{{ $sertifikat->tanggal_upload ? \Carbon\Carbon::parse($sertifikat->tanggal_upload)->format('d M Y') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;">Belum ada sertifikat diunggah.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="clear:both;"></div>
    <div class="ttd">
        <div>
            {{ \Carbon\Carbon::createFromTimestamp(request('timestamp', time()))->translatedFormat('l, d F Y') }}
        </div>
        <div style="margin-top: 60px;">__________________________</div>
        <div style="margin-top: 5px;">Tanda Tangan Mahasiswa</div>
        <div style="margin-top: 30px; font-weight: bold;">{{ $detailUser->name ?? '-' }}</div>
    </div>
    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::createFromTimestamp(request('timestamp', time()))->format('d/m/Y H:i') }}
    </div>
</body>
</html>
