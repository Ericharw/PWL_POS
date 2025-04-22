@if($penjualanDetails->count() == 0)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-danger shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i>Kesalahan</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Data Tidak Ditemukan</h5>
                    Data yang anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/penjualan') }}" class="btn btn-outline-warning mt-2"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/penjualan/' . $penjualanDetails[0]->penjualan_id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content shadow">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-edit mr-2"></i> Edit Data Transaksi Penjualan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="penjualan_kode" class="form-label">Kode Penjualan</label>
                        <input type="text" name="penjualan_kode" value="{{ $penjualanDetails[0]->penjualan->penjualan_kode }}" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="user_id" class="form-label">Nama Penjual</label>
                        <select name="user_id" class="form-control" required>
                            <option value="">- Pilih User -</option>
                            @foreach($user as $u)
                                <option value="{{ $u->user_id }}" {{ $penjualanDetails[0]->penjualan->user_id == $u->user_id ? 'selected' : '' }}>
                                    {{ $u->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="penjualan_tanggal" class="form-label">Tanggal Penjualan</label>
                        <input type="datetime-local" name="penjualan_tanggal" value="{{ \Carbon\Carbon::parse($penjualanDetails[0]->penjualan->penjualan_tanggal)->format('Y-m-d\TH:i') }}" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="pembeli" class="form-label">Nama Pembeli</label>
                        <input type="text" name="pembeli" value="{{ $penjualanDetails[0]->penjualan->pembeli }}" class="form-control" required>
                    </div>

                    <hr>
                    <h5 class="mt-4"><i class="fas fa-boxes mr-2"></i>Detail Barang</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered mt-3">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penjualanDetails as $index => $detail)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="details[{{ $index }}][detail_id]" value="{{ $detail->detail_id }}">
                                            <select name="details[{{ $index }}][barang_id]" class="form-control" required>
                                                <option value="">- Pilih Barang -</option>
                                                @foreach($barang as $b)
                                                    <option value="{{ $b->barang_id }}" {{ $detail->barang_id == $b->barang_id ? 'selected' : '' }}>
                                                        {{ $b->barang_nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="details[{{ $index }}][jumlah]" value="{{ $detail->jumlah }}" class="form-control" min="1" required>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <a href="{{ url('/penjualan') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>

            </div>
        </div>
    </form>
@endif
