@extends('layouts.template')

@section('content')

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('supplier') }}" class="form-horizontal">
            @csrf
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">ID</label>
                <div class="col-11">
                    <input type="text" class="form-control" id="supplier_id" name="supplier_id"
                        value="{{ old('supplier_id') }}" required>
                    @error('supplier_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Nama</label>
                <div class="col-11">
                    <input type="text" class="form-control" id="supplier_nama" name="supplier_nama"
                        value="{{ old('supplier_nama') }}" required>
                    @error('supplier_nama')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Alamat</label>
                <div class="col-11">
                    <input type="text" class="form-control" id="alamat" name="alamat" 
                        value="{{ old('alamat') }}" required>
                    @error('alamat')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Telepon</label>
                <div class="col-11">
                    <input type="text" class="form-control" id="telepon" name="telepon" 
                        value="{{ old('telepon') }}" required>
                    @error('telepon')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label"></label>
                <div class="col-11">
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    <a class="btn btn-sm btn-default ml-1" href="{{ url('supplier') }}">Kembali</a>
                </div>
            </div>
        </form>
        
    </div>
</div>

@endsection

@push('css')
@endpush
@push('js')
@endpush