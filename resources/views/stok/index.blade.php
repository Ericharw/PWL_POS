@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title ?? 'Daftar Stok' }}</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/stok/import') }}')" class="btn btn-sm btn-info mt-1">Import Data Stok</button>
            <a href="{{ url('/stok/export_excel') }}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export Excel</a>   
            <a href="{{ url('/stok/export_pdf') }}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export PDF</a>
            <button onclick="modalAction('{{ url('/stok/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table table-bordered table-striped table-hover table-sm" id="table_stok">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Supplier</th>
                    <th>Barang</th>
                    <th>User</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
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
        $.get(url, function(response) {
            $("#modal-crud .modal-content").html(response);
            $("#modal-crud").modal("show");
        });
    }

    // Bersihkan isi modal setelah ditutup
    $('#modal-crud').on('hidden.bs.modal', function () {
        $("#modal-crud .modal-content").html("");
    });

    var dataStok;
    $(document).ready(function() {
        dataStok = $('#table_stok').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('stok/list') }}",
                type: "GET",
                dataType: "json"
            },
            columns: [
                { data: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false },
                { data: "supplier.supplier_nama", orderable: true, searchable: true },
                { data: "barang.barang_nama", orderable: true, searchable: true },
                { data: "user.nama", orderable: true, searchable: true },
                { data: "stok_tanggal", orderable: true, searchable: true },
                { data: "stok_jumlah", orderable: true, searchable: true },
                { data: "aksi", className: "text-center", orderable: false, searchable: false }
            ]
        });
    });
</script>
@endpush
