@extends('layouts.template')

@section('content')

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title ?? 'Daftar Supplier' }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('supplier/create') }}">Tambah</a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table table-bordered table-striped table-hover table-sm" id="table_supplier">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@endsection

@push('css')
@endpush

@push('js')

<script>
    $(document).ready(function() {
        $('#table_supplier').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('supplier/list') }}",
                type: "GET",
            },
            columns: [
                { data: "supplier_id", className: "text-center", orderable: true, searchable: false },
                { data: "supplier_nama", orderable: true, searchable: true },
                { data: "alamat", orderable: true, searchable: true },
                { data: "telepon", orderable: true, searchable: true },
                { data: "aksi", orderable: false, searchable: false }
            ]
        });
    });
</script>

@endpush
