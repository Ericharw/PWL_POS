@empty($penjualan)
    <div id="myModal" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
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
    <div id="myModal" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $penjualan->penjualan_id }}</td>
                    </tr>
                    <tr>
                        <th>Pembeli</th>
                        <td>{{ $penjualan->pembeli }}</td>
                    </tr>
                    <tr>
                        <th>Kode</th>
                        <td>{{ $penjualan->penjualan_kode }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $penjualan->penjualan_tanggal }}</td>
                    </tr>
                </table>
                <div class="table-responsive">
                    <table id="detail-table" class="table table-bordered rounded overflow-hidden">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Barang</th>
                                <th>Harga Barang</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody id="detail-items">
                            @foreach ($penjualan->detail as $i => $detail)
                                <tr class="detail-row">
                                    <td class="text-center">
                                        <span class="no">{{ $i++ }}</span>
                                    </td>
                                    <td>
                                        {{ $detail->barang->barang_nama }}
                                    </td>
                                    <td>
                                        {{ number_format($detail->barang->harga_jual, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        {{ $detail->jumlah }}
                                    </td>
                                    <td class="detail-harga">
                                        {{ number_format($detail->harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right">Total</th>
                                <th id="total-amount" class="text-right">0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
            </div>
        </div>
    </div>
@endempty

<script>
    $(document).ready(function() {
        calculateTotal();
    });

    function calculateTotal() {
        let total = 0;
        $('.detail-harga').each(function() {
            let harga = $(this).text().replace(/\./g, '').replace(/,/g, '.');
            total += parseFloat(harga);
        });
        $('#total-amount').text(total.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }));
    }
</script>