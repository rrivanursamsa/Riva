<?php

namespace App\Http\Controllers;

use App\Models\Merek;
use App\Models\Produk;
use Illuminate\Http\Request;
use PDF;
use Storage;

class ProdukController extends Controller
{
    public function exportPdf()
    {
        $produk = Produk::latest()->get();

        $data = [
            'title' => 'Data Produk',
            'date' => date('m/d/Y'),
            'produk' => $produk,
        ];

        $pdf = PDF::loadView('produk.export-pdf', $data)
            ->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

    public function index()
    {
        $produk = Produk::latest()->get();
        return view('produk.index', compact('produk'));
    }

    public function create()
    {
        $merek = Merek::all();
        return view('produk.create', compact('merek'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|min:5',
            'harga' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'deskripsi' => 'required|min:10',
        ]);

        $produk = new Produk();
        $produk->nama = $request->nama;
        $produk->harga = $request->harga;
        $produk->deskripsi = $request->deskripsi;
        $produk->id_merek = $request->id_merek;

        $image = $request->file('image');
        $image->storeAs('public/produks', $image->hashName());
        $produk->image = $image->hashName();

        $produk->save();
        return redirect()->route('produk.index');
    }

    public function show($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.show', compact('produk'));
    }

    public function edit($id)
    {
        $merek = Merek::all();
        $produk = Produk::findOrFail($id);
        return view('produk.edit', compact('produk', 'merek'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama' => 'required|min:5',
            'harga' => 'required',
            'deskripsi' => 'required|min:10',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->nama = $request->nama;
        $produk->harga = $request->harga;
        $produk->deskripsi = $request->deskripsi;
        $produk->id_merek = $request->id_merek;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/produks', $image->hashName());
            Storage::delete('public/produks/' . $produk->image);
            $produk->image = $image->hashName();
        }

        $produk->save();
        return redirect()->route('produk.index');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        Storage::delete('public/produks/' . $produk->image);
        $produk->delete();
        return redirect()->route('produk.index');
    }
}
