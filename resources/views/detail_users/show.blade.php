<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 10px;">
        <div class="modal-header bg-primary text-white" style="border-radius: 8px 8px 0 0;">
            <h5 class="modal-title" id="exampleModalLabel">
                <i class="fas fa-user mr-2"></i>Detail Pengguna
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body p-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                        <span class="text-muted"><i class="fas fa-id-card mr-2"></i>No Induk</span>
                        <span class="font-weight-bold text-primary">{{ $detailUser->no_induk ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                        <span class="text-muted"><i class="fas fa-user mr-2"></i>Nama Lengkap</span>
                        <span class="font-weight-bold text-dark">{{ $detailUser->name ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                        <span class="text-muted"><i class="fas fa-envelope mr-2"></i>Email</span>
                        <span class="font-weight-bold text-dark">{{ $detailUser->user->email }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                        <span class="text-muted"><i class="fas fa-graduation-cap mr-2"></i>Program Studi</span>
                        <span class="font-weight-bold text-dark">{{ $detailUser->prodi->name ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                        <span class="text-muted"><i class="fas fa-layer-group mr-2"></i>Level</span>
                        <span class="font-weight-bold text-dark">{{ $detailUser->user->level->nama_level }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                        <span class="text-muted"><i class="fas fa-venus-mars mr-2"></i>Jenis Kelamin</span>
                        <span class="font-weight-bold text-dark">
                            {{ $detailUser->jenis_kelamin == 'L' ? 'Laki-laki' : ($detailUser->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                        <span class="text-muted"><i class="fas fa-phone mr-2"></i>No Telepon</span>
                        <span class="font-weight-bold text-dark">{{ $detailUser->phone ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>