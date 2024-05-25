<?php

namespace App\Http\Controllers;

use App\Models\Merek;
use Illuminate\Http\Request;

class MerekController extends Controller
{

    public function index()
    {
        $merek = Merek::latest()->get();
        return view('merek.index', compact('merek'));
    }

    public function create()
    {
        return view('merek.create');
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'nama_merek' => 'required|unique:mereks',
        ]);

        $merek = new Merek();
        $merek->nama_merek = $request->nama_merek;
        $merek->save();
        return redirect()->route('merek.index');
    }

    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $merek = Merek::findOrFail($id);
        return view('merek.edit', compact('merek'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama_merek' => 'required',
        ]);

        $merek = Merek::findOrFail($id);
        $merek->nama_merek = $request->nama_merek;
        $merek->save();
        return redirect()->route('merek.index');
    }

    public function destroy(string $id)
    {
        $merek = Merek::findOrFail($id);
        $merek->delete();
        return redirect()->route('merek.index');

    }
}
