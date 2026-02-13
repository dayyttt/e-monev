<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;

class BidangController extends Controller
{
    public function index()
    {
        $bidangs = Bidang::withCount('kegiatans')->orderBy('order')->get();
        return view('bidang.index', compact('bidangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_bidang' => 'required|unique:bidangs',
            'nama_bidang' => 'required',
            'singkatan' => 'nullable',
            'deskripsi' => 'nullable',
            'kepala_bidang' => 'nullable',
            'nip_kepala' => 'nullable',
            'telepon' => 'nullable',
            'email' => 'nullable|email',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $validated['order'] = Bidang::max('order') + 1;
        $bidang = Bidang::create($validated);

        return response()->json(['success' => true, 'data' => $bidang]);
    }

    public function update(Request $request, Bidang $bidang)
    {
        $validated = $request->validate([
            'kode_bidang' => 'required|unique:bidangs,kode_bidang,' . $bidang->id,
            'nama_bidang' => 'required',
            'singkatan' => 'nullable',
            'deskripsi' => 'nullable',
            'kepala_bidang' => 'nullable',
            'nip_kepala' => 'nullable',
            'telepon' => 'nullable',
            'email' => 'nullable|email',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $bidang->update($validated);

        return response()->json(['success' => true, 'data' => $bidang]);
    }

    public function destroy(Bidang $bidang)
    {
        $bidang->delete();
        return response()->json(['success' => true]);
    }

    public function reorder(Request $request)
    {
        $items = $request->input('items');
        
        foreach ($items as $index => $id) {
            Bidang::where('id', $id)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function show(Bidang $bidang)
    {
        $bidang->load(['kegiatans.subKegiatans.rincianKegiatans']);
        return view('bidang.show', compact('bidang'));
    }
}
