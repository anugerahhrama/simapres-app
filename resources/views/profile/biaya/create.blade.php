{{-- filepath: resources/views/profile/biaya/create.blade.php --}}
<form action="{{ route('profile.biaya.store') }}" method="POST" id="form-tambah-biaya">
    @csrf
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-dark" style="background-color: #f0f5fe;">
                <h5 class="modal-title">
                    <i class="fas fa-money-bill-wave mr-2"></i>Tambah Preferensi Biaya Pendaftaran
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <label>Pilih Biaya Pendaftaran</label>
                <select name="biaya" class="form-control" required>
                    <option value="">-- Pilih Biaya --</option>
                    <option value="gratis">Gratis</option>
                    <option value="berbayar">Berbayar</option>
                </select>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" data-dismiss="modal" class="btn btn-light border shadow-sm">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <button type="submit" class="btn btn-primary shadow-sm">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</form>