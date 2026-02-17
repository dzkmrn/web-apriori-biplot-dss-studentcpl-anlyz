<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\DataHistori;
use Illuminate\Support\Facades\DB;

class ResetDataController extends Controller
{
    public function index()
    {
        $totalMahasiswa = Mahasiswa::count();
        $mahasiswaWithCPL = Mahasiswa::whereNotNull('nilai_cpl')
            ->where('nilai_cpl', '!=', '{}')
            ->where('nilai_cpl', '!=', '')
            ->where('nilai_cpl', '!=', 'null')
            ->count();
        $totalAnalysis = DataHistori::count();
        
        return view('reset-data.index', compact('totalMahasiswa', 'mahasiswaWithCPL', 'totalAnalysis'));
    }
    
    public function reset(Request $request)
    {
        try {
            // Get count before deletion
            $deletedCount = Mahasiswa::count();
            
            // Truncate cannot be used within transactions, so we use it directly
            Mahasiswa::truncate(); // This will delete all records and reset auto-increment
            
            return redirect()->route('reset-data.index')
                ->with('success', "Berhasil menghapus semua {$deletedCount} mahasiswa dari database. Data histori analisis tetap tersimpan. Silakan import data mahasiswa yang baru.");
                
        } catch (\Exception $e) {
            return redirect()->route('reset-data.index')
                ->with('error', 'Error saat melakukan reset data: ' . $e->getMessage());
        }
    }
    
    public function confirm()
    {
        $totalMahasiswa = Mahasiswa::count();
        // For confirmation, we'll delete all mahasiswa, so mahasiswaWithCPL = totalMahasiswa
        $mahasiswaWithCPL = $totalMahasiswa;
            
        return view('reset-data.confirm', compact('totalMahasiswa', 'mahasiswaWithCPL'));
    }
}
