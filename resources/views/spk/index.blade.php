@extends('layouts.app')

@section('content')
    <div class="card mt-4">
        <div class="card-header" style="background: white; border-bottom: 1px solid #e2e8f0; padding: 15px 20px;">
            <div class="mb-5 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Perhitungan SPK (WASPAS) — Tahap per Tahap</h4>

                <button onclick="modalAction('{{ route('spk.edit', auth()->user()->id) }}')" type="button" class="btn btn-primary">
                    Setting Bobot
                </button>
            </div>

            {{-- 1. Nilai Asli C1–C5 --}}
            <h5 class="mt-4">1. Nilai Kriteria</h5>
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Alternatif</th>
                        <th>C1 (Keahlian)</th>
                        <th>C2 (Jenis)</th>
                        <th>C3 (Biaya)</th>
                        <th>C4 (Tingkatan)</th>
                        <th>C5 (Benefit)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hasil as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $row['lomba']->judul }}</td>
                            @foreach (['c1', 'c2', 'c3', 'c4', 'c5'] as $c)
                                <td>{{ $row['nilai'][$c] }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- 2. Tabel Normalisasi --}}
            <h5 class="mt-5">2. Hasil Normalisasi</h5>
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Alternatif</th>
                        <th>C1</th>
                        <th>C2</th>
                        <th>C3</th>
                        <th>C4</th>
                        <th>C5</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hasil as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $row['lomba']->judul }}</td>
                            @foreach (['c1', 'c2', 'c3', 'c4', 'c5'] as $c)
                                <td>{{ number_format($row['normalisasi'][$c], 4) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- 3. Perkalian Bobot + WSM + WPM --}}
            <h5 class="mt-5">3. Perkalian Bobot & Hasil WASPAS</h5>
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Alternatif</th>
                        <th>WSM</th>
                        <th>WPM</th>
                        <th>WASPAS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hasil as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $row['lomba']->judul }}</td>
                            <td>{{ number_format($row['wsm'], 4) }}</td>
                            <td>{{ number_format($row['wpm'], 4) }}</td>
                            <td><strong>{{ number_format($row['waspas'], 4) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- 4. Ranking Akhir --}}
            <h5 class="mt-5">4. Ranking Alternatif</h5>
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Ranking</th>
                        <th>Alternatif</th>
                        <th>Skor WASPAS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hasil as $row)
                        <tr>
                            <td><strong>{{ $row['ranking'] }}</strong></td>
                            <td>{{ $row['lomba']->judul }}</td>
                            <td>{{ number_format($row['waspas'], 4) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="100%" aria-hidden="true"></div>
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
    </script>
@endpush
