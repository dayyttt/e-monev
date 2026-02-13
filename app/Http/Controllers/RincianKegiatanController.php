<?php

namespace App\Http\Controllers;

use App\Models\RincianKegiatan;
use Illuminate\Http\Request;

class RincianKegiatanController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sub_kegiatan_id' => 'required|exists:sub_kegiatans,id',
            'kode_rincian' => 'required|unique:rincian_kegiatans',
            'nama_rincian' => 'required',
            'deskripsi' => 'nullable',
            'anggaran' => 'required|numeric|min:0',
            'progress' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:draft,aktif,selesai'
        ]);

        $validated['order'] = RincianKegiatan::where('sub_kegiatan_id', $validated['sub_kegiatan_id'])->max('order') + 1;
        $rincian = RincianKegiatan::create($validated);

        return response()->json(['success' => true, 'data' => $rincian]);
    }

    public function update(Request $request, RincianKegiatan $rincianKegiatan)
    {
        $validated = $request->validate([
            'kode_rincian' => 'required|unique:rincian_kegiatans,kode_rincian,' . $rincianKegiatan->id,
            'nama_rincian' => 'required',
            'deskripsi' => 'nullable',
            'anggaran' => 'required|numeric|min:0',
            'progress' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:draft,aktif,selesai'
        ]);

        $rincianKegiatan->update($validated);

        return response()->json(['success' => true, 'data' => $rincianKegiatan]);
    }

    public function destroy(RincianKegiatan $rincianKegiatan)
    {
        $rincianKegiatan->delete();
        return response()->json(['success' => true]);
    }

    public function reorder(Request $request)
    {
        $items = $request->input('items');
        $subKegiatanId = $request->input('sub_kegiatan_id');
        
        foreach ($items as $index => $id) {
            RincianKegiatan::where('id', $id)->update([
                'order' => $index,
                'sub_kegiatan_id' => $subKegiatanId
            ]);
        }

        return response()->json(['success' => true]);
    }
}
