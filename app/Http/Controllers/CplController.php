<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cpl;
use Illuminate\Support\Facades\Validator;

class CplController extends Controller
{
    public function index()
    {
        $cpls = Cpl::orderBy('kode_cpl')->paginate(20);
        
        // Statistics
        $totalCpl = Cpl::count();
        $activeCpl = Cpl::where('is_active', true)->count();
        $inactiveCpl = Cpl::where('is_active', false)->count();
        $totalKategori = Cpl::distinct('kategori')->count('kategori');
        
        // Chart data
        $categoryStats = Cpl::selectRaw('kategori, count(*) as total')
            ->groupBy('kategori')
            ->get();
            
        $chartData = [
            'categories' => [
                'labels' => $categoryStats->pluck('kategori')->toArray(),
                'data' => $categoryStats->pluck('total')->toArray()
            ]
        ];
        
        return view('cpl.index', compact(
            'cpls', 
            'totalCpl', 
            'activeCpl', 
            'inactiveCpl', 
            'totalKategori',
            'chartData'
        ));
    }

    public function create()
    {
        return view('cpl.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_cpl' => 'required|unique:cpls,kode_cpl|max:20',
            'deskripsi' => 'required|string',
            'kategori' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Cpl::create([
            'kode_cpl' => $request->kode_cpl,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
            'is_active' => true
        ]);

        return redirect()->route('cpl.index')
            ->with('success', 'CPL berhasil ditambahkan');
    }

    public function show(Cpl $cpl)
    {
        // Get statistics
        $mahasiswaCount = \App\Models\Mahasiswa::whereNotNull('nilai_cpl')->count();
        $analysisCount = \App\Models\DataHistori::count(); // Simplified for now
        
        return view('cpl.show', compact('cpl', 'mahasiswaCount', 'analysisCount'));
    }

    public function edit(Cpl $cpl)
    {
        return view('cpl.edit', compact('cpl'));
    }

    public function update(Request $request, Cpl $cpl)
    {
        $validator = Validator::make($request->all(), [
            'kode_cpl' => 'required|unique:cpls,kode_cpl,' . $cpl->id . '|max:20',
            'deskripsi' => 'required|string',
            'kategori' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $cpl->update([
            'kode_cpl' => $request->kode_cpl,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('cpl.index')
            ->with('success', 'CPL berhasil diperbarui');
    }

    public function destroy(Cpl $cpl)
    {
        $cpl->delete();
        
        return redirect()->route('cpl.index')
            ->with('success', 'CPL berhasil dihapus');
    }

    public function toggleActive(Cpl $cpl)
    {
        $cpl->update([
            'is_active' => !$cpl->is_active
        ]);
        
        $status = $cpl->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return response()->json([
            'success' => true,
            'message' => "CPL berhasil {$status}",
            'is_active' => $cpl->is_active
        ]);
    }
}