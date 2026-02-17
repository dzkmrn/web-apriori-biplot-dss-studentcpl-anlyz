<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfilLulusan;
use Illuminate\Support\Facades\Validator;

class ProfilLulusanController extends Controller
{
    public function index()
    {
        $profilLulusans = ProfilLulusan::orderBy('nama_profil')->paginate(20);
        return view('profil-lulusan.index', compact('profilLulusans'));
    }

    public function create()
    {
        return view('profil-lulusan.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_profil' => 'required|string|max:255|unique:profil_lulusans,nama_profil',
            'deskripsi' => 'nullable|string',
            'jumlah' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        ProfilLulusan::create([
            'nama_profil' => $request->nama_profil,
            'deskripsi' => $request->deskripsi,
            'jumlah' => $request->jumlah,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('profil-lulusan.index')
            ->with('success', 'Profil lulusan berhasil ditambahkan');
    }

    public function show(ProfilLulusan $profilLulusan)
    {
        $totalAnalysis = \App\Models\DataHistori::count(); // Simplified for now
        
        return view('profil-lulusan.show', compact('profilLulusan', 'totalAnalysis'));
    }

    public function edit(ProfilLulusan $profilLulusan)
    {
        return view('profil-lulusan.edit', compact('profilLulusan'));
    }

    public function update(Request $request, ProfilLulusan $profilLulusan)
    {
        $validator = Validator::make($request->all(), [
            'nama_profil' => 'required|string|max:255|unique:profil_lulusans,nama_profil,' . $profilLulusan->id,
            'deskripsi' => 'nullable|string',
            'jumlah' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $profilLulusan->update([
            'nama_profil' => $request->nama_profil,
            'deskripsi' => $request->deskripsi,
            'jumlah' => $request->jumlah,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('profil-lulusan.index')
            ->with('success', 'Profil lulusan berhasil diperbarui');
    }

    public function destroy(ProfilLulusan $profilLulusan)
    {
        $profilLulusan->delete();
        
        return redirect()->route('profil-lulusan.index')
            ->with('success', 'Profil lulusan berhasil dihapus');
    }

    public function toggleActive(ProfilLulusan $profilLulusan)
    {
        $profilLulusan->update([
            'is_active' => !$profilLulusan->is_active
        ]);
        
        $status = $profilLulusan->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return response()->json([
            'success' => true,
            'message' => "Profil lulusan berhasil {$status}",
            'is_active' => $profilLulusan->is_active
        ]);
    }
}
