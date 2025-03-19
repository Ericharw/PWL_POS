@empty($supplier)
    <div id="modal-crud" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/supplier') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/supplier/' . $supplier->supplier_id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div class="modal-header">
            <h5 class="modal-title">Edit Data Supplier</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Nama Supplier</label>
                <input type="text" name="supplier_nama" id="supplier_nama" class="form-control"
                    value="{{ $supplier->supplier_nama }}" required>
                <small id="error-nama" class="error-text form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label>Alamat Supplier</label>
                <input type="text" name="supplier_alamat" id="supplier_alamat" class="form-control"
                    value="{{ $supplier->supplier_alamat }}" required>
                <small id="error-alamat" class="error-text form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label>Telepon Supplier</label>
                <input type="text" name="supplier_telepon" id="supplier_telepon" class="form-control"
                    value="{{ $supplier->supplier_telepon }}" required>
                <small id="error-telepon" class="error-text form-text text-danger"></small>
            </div>
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
                    supplier_nama: { required: true, minlength: 3, maxlength: 100 },
                    supplier_alamat: { required: true, minlength: 5, maxlength: 100 },
                    supplier_telepon: { required: true, minlength: 10, maxlength: 15 }
                },
                messages: {
                    supplier_nama: {
                        required: "Nama supplier harus diisi.",
                        minlength: "Nama supplier minimal 3 karakter.",
                        maxlength: "Nama supplier maksimal 100 karakter."
                    },
                    supplier_alamat: {
                        required: "Alamat supplier harus diisi.",
                        minlength: "Alamat supplier minimal 5 karakter.",
                        maxlength: "Alamat supplier maksimal 100 karakter."
                    },
                    supplier_telepon: {
                        required: "Telepon supplier harus diisi.",
                        minlength: "Telepon supplier minimal 10 karakter.",
                        maxlength: "Telepon supplier maksimal 15 karakter."
                    }
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
                                dataSupplier.ajax.reload(); // Reload datatable
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
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: 'Gagal menyimpan data. Silakan coba lagi.'
                            });
                        }
                    });
                    return false; // Mencegah form submit default
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