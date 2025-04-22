@empty($stok)
    <div id="modal-crud" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/stok') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/stok/' . $stok->stok_id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div class="modal-header">
            <h5 class="modal-title">Edit Data Stok</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Supplier Stok</label>
                <select name="supplier_id" id="supplier_id" class="form-control" required>
                    <option value="">- Pilih Supplier -</option>
                    @foreach ($suppliers as $s)
                        <option value="{{ $s->supplier_id }}" {{ $s->supplier_id == $stok->supplier->supplier_id ? 'selected' : '' }}>
                            {{ $s->supplier_nama }}
                        </option>
                    @endforeach
                </select>
                <small id="error-supplier_id" class="error-text form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label>Barang Stok</label>
                <select name="barang_id" id="barang_id" class="form-control" required>
                    <option value="">- Pilih Barang -</option>
                    @foreach ($barangs as $b)
                        <option value="{{ $b->barang_id }}" {{ $b->barang_id == $stok->barang->barang_id ? 'selected' : '' }}>
                            {{ $b->barang_nama }}
                        </option>
                    @endforeach
                </select>
                <small id="error-barang_id" class="error-text form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label>User Stok</label>
                <input type="text" name="user_nama" id="user_nama" class="form-control" value="{{ $stok->user->nama }}" disabled>
                <small id="error-user_nama" class="error-text form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label>Tanggal</label>
                <input type="text" name="stok_tanggal" id="stok_tanggal" class="form-control" value="{{ $stok->stok_tanggal }}" required>
                <small id="error-stok_tanggal" class="error-text form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label>Jumlah</label>
                <input type="text" name="stok_jumlah" id="stok_jumlah" class="form-control" value="{{ $stok->stok_jumlah }}" required>
                <small id="error-stok_jumlah" class="error-text form-text text-danger"></small>
            </div>
            <input type="hidden" name="user_id" id="user_id" value="{{ $stok->user->user_id }}">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>

    <script>
        $(document).ready(function () {
            $("#form-edit").validate({
                rules: {
                    supplier_id: { required: true, number: true },
                    barang_id: { required: true, number: true },
                    user_id: { required: true, number: true },
                    stok_tanggal: { required: true, date: true },
                    stok_jumlah: { required: true, number: true }
                },
                messages: {
                    supplier_id: { required: "Supplier harus dipilih.", number: "ID Supplier tidak valid." },
                    barang_id: { required: "Barang harus dipilih.", number: "ID Barang tidak valid." },
                    user_id: { required: "User tidak ditemukan.", number: "ID User tidak valid." },
                    stok_tanggal: { required: "Tanggal harus diisi.", date: "Format tanggal tidak valid." },
                    stok_jumlah: { required: "Jumlah harus diisi.", number: "Jumlah harus berupa angka." }
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function (response) {
                            if (response.status) {
                                $('#modal-crud').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                dataStok.ajax.reload(); // Reload datatable stok
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function (prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: 'Gagal menyimpan data. Silakan coba lagi.'
                            });
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty