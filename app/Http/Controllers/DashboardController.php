<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\DataHistori;
use App\Models\AssociationRule;
use App\Models\Cpl;
use App\Models\ProfilLulusan;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalMahasiswa = Mahasiswa::count();
        $totalCPL = Cpl::active()->count();
        $totalAnalisis = DataHistori::count();
        $totalRules = AssociationRule::count();

        // Recent analysis
        $recentAnalysis = DataHistori::with('associationRules')
            ->latest()
            ->take(5)
            ->get();

        // Chart data for CPL distribution
        $cplDistributionData = $this->getCplDistributionData();
        
        // Top association rules
        $topRules = AssociationRule::with('dataHistori')
            ->orderByConfidence('desc')
            ->take(10)
            ->get();

        // Angkatan data
        $angkatanData = Mahasiswa::selectRaw('angkatan, count(*) as total')
            ->whereNotNull('angkatan')
            ->groupBy('angkatan')
            ->orderBy('angkatan', 'desc')
            ->get();

        return view('dashboard.index', compact(
            'totalMahasiswa',
            'totalCPL', 
            'totalAnalisis',
            'totalRules',
            'recentAnalysis',
            'cplDistributionData',
            'topRules',
            'angkatanData'
        ));
    }

    private function getCplDistributionData()
    {
        $mahasiswas = Mahasiswa::whereNotNull('nilai_cpl')->get();
        $distributionData = [
            'Baik' => 0,
            'Cukup' => 0,
            'Kurang' => 0,
            'Missing' => 0
        ];

        foreach ($mahasiswas as $mahasiswa) {
            if ($mahasiswa->nilai_cpl) {
                foreach ($mahasiswa->nilai_cpl as $cplCode => $nilai) {
                    $kategori = $mahasiswa->kategorikanCpl($nilai);
                    if (isset($distributionData[$kategori])) {
                        $distributionData[$kategori]++;
                    }
                }
            }
        }

        return $distributionData;
    }

    public function getChartData(Request $request)
    {
        $type = $request->get('type', 'cpl_distribution');
        
        switch ($type) {
            case 'cpl_distribution':
                return response()->json($this->getCplDistributionData());
                
            case 'angkatan_stats':
                $angkatanStats = Mahasiswa::selectRaw('angkatan, count(*) as total')
                    ->whereNotNull('angkatan')
                    ->groupBy('angkatan')
                    ->orderBy('angkatan')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'angkatan' => $item->angkatan,
                            'total' => $item->total
                        ];
                    });
                return response()->json($angkatanStats);
                
            case 'rules_metrics':
                $rulesMetrics = AssociationRule::select('confidence', 'lift', 'support', 'rule_type')
                    ->get()
                    ->map(function ($rule) {
                        return [
                            'confidence' => $rule->confidence,
                            'lift' => $rule->lift,
                            'support' => $rule->support,
                            'rule_type' => $rule->rule_type
                        ];
                    });
                return response()->json($rulesMetrics);
                
            default:
                return response()->json([]);
        }
    }
}
