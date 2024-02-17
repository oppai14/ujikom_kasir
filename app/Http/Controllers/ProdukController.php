<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\produk;
use App\Models\kategori;
use App\Models\produkdetail;
use App\Models\vwproduk;
use App\Models\Kategori as KategoriModel;

class ProdukController extends Controller
{
    //index
    public function index(){
        $produk = vwProduk::all();
        return view('produk.index', ['datas' => $produk]);
        // return view('produk.index');
    }

    //halaman tambah
    public function create()
    {
        $produk = produk::all();
        $kategori = kategori::all();
        return view('produk.create',  ['datas' => $produk, 'kategori' => $kategori]);
        // return view('produk.create');
    }

    //kitim data tambah
    public function store(Request $request)
{
    $request->validate([
        'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('gambar')) {
        $gambar = $request->file('gambar');
        $nama_gambar = time() . '_' . $gambar->getClientOriginalName();
        $lokasi = public_path('uploads');
        $gambar->move($lokasi, $nama_gambar);
        echo "Gambar berhasil diunggah.";
        $request['gambar'] = $nama_gambar;
    } else {
        echo "Gagal mengunggah gambar.";
        return redirect()->back()->with('error', 'Gagal mengunggah gambar.');
    }

    $nilai_produk = [
        'nama'          => $request->nama,
    ];
    // produk::create($nilai_produk)
    $id_produk = produk::create($nilai_produk)->id;
    $nilai_produk_detail = [
        'id_produk'     => $id_produk,
        'gambar'        => $nama_gambar,
        'id_kategori'   => $request->kategori,
        'hpp'           => $request->hpp,
        'harga_jual'    => $request->harga_jual,
        'stok'          => $request->stok
    ];

    produkdetail::create($nilai_produk_detail);
    // var_dump($nilai);exit;
    // Tampilkan nilai jika proses pengiriman data gagal
    // if (!) {
    //     var_dump($nilai);
    //     exit(); // Hentikan eksekusi script jika ingin menampilkan hanya saat gagal
    // }

    return redirect()->route('produk.index')->with('success', 'Produk berhasil disimpan');
}



    // halaman edit
    public function edit($id){
       // Menggunakan first() untuk mendapatkan satu hasil
    $produk = vwproduk::where('id', $id)->first();
    $kategori = kategori::all();
    
    // Memastikan produk ditemukan sebelum diteruskan ke view
    if(!$produk){
        return redirect()->route('produk.index')->with('error', 'Produk tidak ditemukan');
    }

    // Mengirimkan data produk dan kategori ke view
    return view('produk.edit', ['data' => $produk, 'kategori' => $kategori]);
    }

    //kirim data edit
    public function update(Request $request, Produk $data)
    {
        $nilai = [
            'nama'          => $request->nama,
            'gambar'        => $request->gambar,
            'id_kategori'   => $request->kategori,
            'hpp'           => $request->hpp,
            'harga_jual'    => $request->harga_jual,
        ];
    
        $data->where('id', $request->id)->update($nilai);
    
        return redirect()->route('produk.index')->with('success', 'Update Berhasil');
    }

    //hapus data
    public function destroy($id)
    {
        Produk::findOrFail($id)->delete();
        produkdetail::where('id_produk', $id)->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
    }
}
