@empty($supplier)
    <div id="modal-delete" class="modal-dialog modal-lg" role="document">
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
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/supplier') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div class="modal-header">
        <h5 class="modal-title">Data Supplier</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <table class="table table-sm table-bordered table-striped">
            <tr>
                <th class="text-right col-3">Nama Supplier :</th>
                <td class="col-9">{{ $supplier->supplier_nama }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">Alamat Supplier :</th>
                <td class="col-9">{{ $supplier->supplier_alamat }}</td>
            </tr>
            <tr>
                <th class="text-right col-3">Telepon :</th>
                <td class="col-9">{{ $supplier->supplier_telepon }}</td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button onclick="modalAction('{{ url('/supplier/' . $supplier->supplier_id . '/edit_ajax') }}')"
            class="btn btn-success btn-sm">Edit
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-primary btn-sm">Close</button>
    </div>
@endempty