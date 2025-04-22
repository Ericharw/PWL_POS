<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\BarangModel;
use App\Models\StokModel;
use App\Models\PenjualanModel;

class WelcomeController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Selamat Datang',
            'list' => ['Home', 'Welcome']
        ];

        $activeMenu = 'dashboard';

        // Ambil data statistik
        $totalPengguna = UserModel::count();
        $totalBarang = BarangModel::count();
        $totalStok = StokModel::sum('stok_jumlah');

        $totalPenjualan = PenjualanModel::with('detail')
            ->get()
            ->flatMap->detail
            ->sum(function ($item) {
                return $item->jumlah * $item->harga; // ganti jika field-nya berbeda
            });

        return view('welcome', compact(
            'breadcrumb',
            'activeMenu',
            'totalPengguna',
            'totalBarang',
            'totalStok',
            'totalPenjualan'
        ));
    }
    
}