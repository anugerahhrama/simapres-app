{{-- resources/views/lomba/show.blade.php --}}
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Lomba</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span>&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <table class="table table-sm table-bordered table-striped">
                <tr>
                    <th class="text-right col-4">Judul Lomba :</th>
                    <td class="col-8">{{ $lomba->judul_lomba ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Tingkatan :</th>
                    <td>{{ ucfirst($lomba->tingkatan) ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Penyelenggara :</th>
                    <td>{{ $lomba->penyelenggara ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Program Studi :</th>
                    <td>{{ $lomba->prodi->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Deskripsi :</th>
                    <td>{{ $lomba->deskripsi ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
