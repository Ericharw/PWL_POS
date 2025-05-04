<form action="{{ url('/penjualan/store_ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Pembeli</label>
                    <input type="text" name="pembeli" class="form-control" required>
                    <small id="error-pembeli" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tanggal Penjualan</label>
                    <input type="text" name="penjualan_tanggal" id="penjualan_tanggal" class="form-control" required>
                    <small id="error-penjualan_tanggal" class="error-text form-text text-danger"></small>
                </div>

                <hr>
                <h6>Barang yang Dijual</h6>
                <div id="list-barang">
                    <div class="barang-item mb-3">
                        <div class="form-row mb-2">
                            <div class="col-8">
                                <select name="barang_id[]" class="form-control" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach ($barang as $b)
                                        <option value="{{ $b->barang_id }}">{{ $b->barang_nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <div class="col-8">
                                <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" required>
                            </div>
                            <div class="col-4">
                                <button type="button" class="btn btn-danger btn-remove">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" id="btn-tambah-barang" class="btn btn-success btn-sm mt-2">+ Tambah Barang</button>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        // Format tanggal lengkap: YYYY-MM-DD HH:MM:SS
        var currentDate = new Date();
        var formattedDate = currentDate.getFullYear() + '-' +
            (currentDate.getMonth() + 1).toString().padStart(2, '0') + '-' +
            currentDate.getDate().toString().padStart(2, '0') + ' ' +
            currentDate.getHours().toString().padStart(2, '0') + ':' +
            currentDate.getMinutes().toString().padStart(2, '0') + ':' +
            currentDate.getSeconds().toString().padStart(2, '0');

        $('#penjualan_tanggal').val(formattedDate);

        // Counter for dynamic barang items
        let barangCounter = 1;

        // Tambah item barang
        $('#btn-tambah-barang').on('click', function () {
            const item = $('.barang-item').first().clone();
            item.find('select').attr('name', `detail[${barangCounter}][barang_id]`).val('');
            item.find('input').attr('name', `detail[${barangCounter}][jumlah]`).val('');
            $('#list-barang').append(item);
            barangCounter++;
        });

        // Hapus item barang
        $(document).on('click', '.btn-remove', function () {
            if ($('.barang-item').length > 1) {
                $(this).closest('.barang-item').remove();
            }
        });

        // Validasi dan submit AJAX
        $("#form-tambah").validate({
            rules: {
                pembeli: { required: true },
                penjualan_tanggal: { required: true }
            },
            messages: {
                pembeli: {
                    required: "Nama pembeli wajib diisi."
                },
                penjualan_tanggal: {
                    required: "Tanggal penjualan wajib diisi."
                }
            },
            errorElement: 'div',
            errorClass: 'invalid-feedback',
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
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
                            dataPenjualan.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                        }
                    }
                });
            }
        });
    });
</script>