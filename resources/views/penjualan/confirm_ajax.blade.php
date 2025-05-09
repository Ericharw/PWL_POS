@empty($penjualan)
    <div id="modal-delete-penjualan" class="modal-dialog modal-lg" role="document">
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
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/penjualan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax') }}" method="POST" id="form-delete-penjualan">
        @csrf
        @method('DELETE')
        <div class="modal-header">
            <h5 class="modal-title">Hapus Data Penjualan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-warning">
                <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                Apakah Anda ingin menghapus data seperti di bawah ini?
            </div>
            <table class="table table-sm table-bordered table-striped">
                <tr>
                    <th class="text-right col-3">Kode Penjualan :</th>
                    <td class="col-9">{{ $penjualan->penjualan_id }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Tanggal Penjualan :</th>
                    <td class="col-9">{{ $penjualan->penjualan_tanggal }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Nama Pembeli :</th>
                    <td class="col-9">{{ $penjualan->pembeli }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Input Oleh :</th>
                    <td class="col-9">{{ $penjualan->user->username ?? '-' }}</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
            <button type="submit" class="btn btn-primary">Ya, Hapus</button>
        </div>
    </form>

    <script>
        $(document).ready(function () {
            $("#form-delete-penjualan").validate({
                rules: {},
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
                                tablePenjualan.ajax.reload();
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
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty
