@extends('layouts.template')

@section('content')

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/barang/import') }}')" class="btn btn-sm btn-info mt-1">Import Barang</button>
            <a href="{{ url('/barang/export_excel') }}" class="btn btn-sm btn-primary mt-1">Export Barang</a>
            <a href="{{ url('/barang/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Barang</a> 
            <button onclick="modalAction('{{ url('/barang/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="kategori_id" name="kategori_id" required>
                            <option value="">- Semua -</option>
                            @foreach($kategori as $item)
                            <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Kategori Barang</small>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-striped table-hover table-sm" id="table_barang">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Kategori Barang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="modal-crud" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')

<script>
    function modalAction(url) {
                // Kosongkan modal sebelum memuat konten baru
                $("#modal-crud .modal-content").html("");

                // Panggil modal melalui AJAX
                $.get(url, function (response) {
                    $("#modal-crud .modal-content").html(response);
                    $("#modal-crud").modal("show");
                });
            }

            // Bersihkan isi modal setelah ditutup
            $('#modal-crud').on('hidden.bs.modal', function () {
                $("#modal-crud .modal-content").html("");
            });
            var dataBarang
    $(document).ready(function() {
            dataBarang = $('#table_barang').DataTable({
            // serverSide: true, jika ingin menggunakan server side processing
            serverSide: true,
            ajax: {
                "url": "{{ url('barang/list') }}",
                "dataType": "json",
                "type": "GET",
                "data": function (d) {
                        d.kategori_id = $('#kategori_id').val();
                }
            },
            columns: [
                {
                    // nomor urut dari laravel datatable addIndexColumn()
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },

                {
                    data: "barang_kode",
                    className: "",
                    orderable: true,
                    searchable: true
                },

                {
                    data: "barang_nama",
                    className: "",
                    orderable: true,
                    searchable: true
                },

                {
                    data: "harga_beli",
                    className: "",
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return "Rp " + new Intl.NumberFormat("id-ID").format(data);
                    }
                },

                {
                    data: "harga_jual",
                    className: "",
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return "Rp " + new Intl.NumberFormat("id-ID").format(data);
                    }
                },

                {
                    data: "kategori.kategori_nama",
                    className: "",
                    orderable: false,
                    searchable: false
                },

                {
                    data: "aksi",
                    className: "",
                    orderable: false,
                    searchable: false
                }
            ]
        });

    $('#kategori_id').change(function() {
        dataBarang.ajax.reload();
    });
});
</script>

@endpush