<?php

namespace App\Http\Controllers;

use App\Models\SubKegiatan;
use Illuminate\Http\Request;

class SubKegiatanController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'kode_sub_kegiatan' => 'required|unique:sub_kegiatans',
            'nama_sub_kegiatan' => 'required',
            'deskripsi' => 'nullable',
            'status' => 'required|in:draft,aktif,selesai'
        ]);

        $validated['order'] = SubKegiatan::where('kegiatan_id', $validated['kegiatan_id'])->max('order') + 1;
        $validated['anggaran'] = 0; // Default 0, akan dihitung otomatis dari rincian
        $subKegiatan = SubKegiatan::create($validated);

        return response()->json(['success' => true, 'data' => $subKegiatan]);
    }

    public function update(Request $request, SubKegiatan $subKegiatan)
    {
        $validated = $request->validate([
            'kode_sub_kegiatan' => 'required|unique:sub_kegiatans,kode_sub_kegiatan,' . $subKegiatan->id,
            'nama_sub_kegiatan' => 'required',
            'deskripsi' => 'nullable',
            'status' => 'required|in:draft,aktif,selesai'
        ]);

        // Jangan update anggaran, biarkan otomatis dari rincian
        $subKegiatan->update($validated);

        return response()->json(['success' => true, 'data' => $subKegiatan]);
    }

    public function destroy(SubKegiatan $subKegiatan)
    {
        $subKegiatan->delete();
        return response()->json(['success' => true]);
    }

    public function reorder(Request $request)
    {
        $items = $request->input('items');
        $kegiatanId = $request->input('kegiatan_id');
        
        foreach ($items as $index => $id) {
            SubKegiatan::where('id', $id)->update([
                'order' => $index,
                'kegiatan_id' => $kegiatanId
            ]);
        }

        return response()->json(['success' => true]);
    }
}
