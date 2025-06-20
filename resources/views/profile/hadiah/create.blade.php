{{-- filepath: resources/views/profile/hadiah/create.blade.php --}}
<form action="{{ route('profile.hadiah.store') }}" method="POST" id="form-tambah-hadiah">
    @csrf
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-dark" style="background-color: #f0f5fe;">
                <h5 class="modal-title">
                    <i class="fas fa-gift mr-2"></i>Tambah Preferensi Hadiah/Benefit
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <label>Pilih Hadiah/Benefit</label>
                <select name="hadiah" class="form-control" required>
                    <option value="">-- Pilih Hadiah/Benefit --</option>
                    <option value="uang">Uang</option>
                    <option value="sertifikat">Sertifikat</option>
                    <option value="trofi">Trofi</option>
                    <option value="benefit">Benefit Lain</option>
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