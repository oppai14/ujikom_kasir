<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\produk;
use App\Models\vwproduk;
use App\Models\Kategori as KategoriModel;

class ProdukController extends Controller
{
    //index
    public function index(){
        $produks = vwProduk::all();
        return view('produk.index', ['datas' => $produks]);
        // return view('produk.index');
    }

    //halaman tambah
    public function create()
    {
        $kategoris = produk::all();
        return view('produk.create',  ['datas' => $kategoris]);
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

    $nilai = [
        'nama'          => $request->nama,
        'gambar'        => $nama_gambar,
        'kategori_id'   => $request->kategori,
        'hpp'           => $request->hpp,
        'harga_jual'    => $request->harga_jual,
    ];
    // var_dump($nilai);exit;
    // Tampilkan nilai jika proses pengiriman data gagal
    if (!produk::create($nilai)) {
        var_dump($nilai);
        exit(); // Hentikan eksekusi script jika ingin menampilkan hanya saat gagal
    }

    return redirect()->route('produk.index')->with('success', 'Produk berhasil disimpan');
}



    // halaman edit
    public function edit($id){
        $produk = Produk::findOrFail($id);
        $kategoris = produk::all();

        return view('produk.edit', ['data' => $produk, 'kategoris' => $kategoris]);
    }

    //kirim data edit
    public function update(Request $request, Produk $data)
    {
        $nilai = [
            'nama'          => $request->nama,
            'gambar'        => $request->gambar,
            'kategori_id'   => $request->kategori,
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
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
    }
}
