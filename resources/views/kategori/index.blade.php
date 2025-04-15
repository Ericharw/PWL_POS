@extends('layouts.template')

@section('content')

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/kategori/import') }}')" class="btn btn-sm btn-info mt-1">Import Data Kategori</button>
            <a href="{{ url('/kategori/export_excel') }}" class="btn btn-sm btn-primary mt-1">Export Data Kategori Barang</a>
            <button onclick="modalAction('{{ url('/kategori/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode</th>
                    <th>Nama</th>
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
            var dataKategori
    $(document).ready(function() {
      dataKategori = $('#table_kategori').DataTable({
          // serverSide: true, jika ingin menggunakan server side processing
          serverSide: true,
          ajax: {
              "url": "{{ url('kategori/list') }}",
              "dataType": "json",
              "type": "GET",
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
                data: "kategori_kode",
                className: "",
                orderable: true,
                searchable: true
            },

            {
                data: "kategori_nama",
                className: "",
                orderable: true,
                searchable: true
            },

            {
                data: "aksi",
                className: "",
                orderable: false,
                searchable: false
            }
        ]
    });
});
</script>

@endpush