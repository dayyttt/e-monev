<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::with(['bidang', 'subKegiatans.rincianKegiatans'])->orderBy('order')->get();
        $bidangs = \App\Models\Bidang::where('status', 'aktif')->orderBy('nama_bidang')->get();
        return view('kegiatan.index', compact('kegiatans', 'bidangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bidang_id' => 'required|exists:bidangs,id',
            'kode_kegiatan' => 'required|unique:kegiatans',
            'nama_kegiatan' => 'required',
            'deskripsi' => 'nullable',
            'status' => 'required|in:draft,aktif,selesai'
        ]);

        $validated['anggaran'] = 0; // Will be auto-calculated from sub kegiatans
        $validated['order'] = Kegiatan::max('order') + 1;
        $kegiatan = Kegiatan::create($validated);

        return response()->json(['success' => true, 'data' => $kegiatan]);
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $validated = $request->validate([
            'bidang_id' => 'required|exists:bidangs,id',
            'kode_kegiatan' => 'required|unique:kegiatans,kode_kegiatan,' . $kegiatan->id,
            'nama_kegiatan' => 'required',
            'deskripsi' => 'nullable',
            'status' => 'required|in:draft,aktif,selesai'
        ]);

        // Don't update anggaran - it's auto-calculated from sub kegiatans
        $kegiatan->update($validated);

        return response()->json(['success' => true, 'data' => $kegiatan]);
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();
        return response()->json(['success' => true]);
    }

    public function reorder(Request $request)
    {
        $items = $request->input('items');
        
        foreach ($items as $index => $id) {
            Kegiatan::where('id', $id)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function redistributeAnggaran()
    {
        \Artisan::call('anggaran:recalculate');
        return response()->json(['success' => true, 'message' => 'Recalculate anggaran berhasil']);
    }
}
