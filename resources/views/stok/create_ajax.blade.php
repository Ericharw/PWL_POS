<form action="{{ url('/stok/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Tambah Data Stok</h5>
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
                    <option value="{{ $s->supplier_id }}">{{ $s->supplier_nama }}</option>
                @endforeach
            </select>
            <small id="error-supplier_id" class="error-text form-text text-danger"></small>
        </div>
        <div class="form-group">
            <label>Barang Stok</label>
            <select name="barang_id" id="barang_id" class="form-control" required>
                <option value="">- Pilih Barang -</option>
                @foreach ($barangs as $b)
                    <option value="{{ $b->barang_id }}">{{ $b->barang_nama }}</option>
                @endforeach
            </select>
            <small id="error-barang_id" class="error-text form-text text-danger"></small>
        </div>
        <div class="form-group">
            <label>User Stok</label>
            <input type="text" name="user_nama" id="user_nama" class="form-control" value="{{ $user->nama }}" disabled>
            <small id="error-user_nama" class="error-text form-text text-danger"></small>
        </div>
        <div class="form-group">
            <label>Tanggal</label>
            <input type="text" name="stok_tanggal" id="stok_tanggal" class="form-control" required>
            <small id="error-stok_tanggal" class="error-text form-text text-danger"></small>
        </div>
        <div class="form-group">
            <label>Jumlah</label>
            <input type="text" name="stok_jumlah" id="stok_jumlah" class="form-control" required>
            <small id="error-stok_jumlah" class="error-text form-text text-danger"></small>
        </div>
        <input type="hidden" name="user_id" id="user_id" value="{{ $user->user_id }}">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
    $(document).ready(function () {
        // Menentukan format tanggal (YYYY-MM-DD HH:MM:SS)
        var currentDate = new Date();
        var formattedDate = currentDate.getFullYear() + '-' + 
                            (currentDate.getMonth() + 1).toString().padStart(2, '0') + '-' + 
                            currentDate.getDate().toString().padStart(2, '0') + ' ' + 
                            currentDate.getHours().toString().padStart(2, '0') + ':' + 
                            currentDate.getMinutes().toString().padStart(2, '0') + ':' + 
                            currentDate.getSeconds().toString().padStart(2, '0');
        
        // Mengisi field stok_tanggal dengan tanggal dan waktu saat ini
        $("#stok_tanggal").val(formattedDate);
        
        // Validasi form
        $("#form-tambah").validate({
            rules: {
                supplier_id: { required: true, number: true },
                barang_id: { required: true, number: true },
                user_id: { required: true, number: true },
                stok_tanggal: { required: true, date: true },
                stok_jumlah: { required: true, number: true }
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
                            dataStok.ajax.reload();
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
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
