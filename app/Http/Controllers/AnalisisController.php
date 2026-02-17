<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\DataHistori;
use App\Models\AssociationRule;
use App\Models\Cpl;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalisisController extends Controller
{
    public function index()
    {
        $dataHistori = DataHistori::with('associationRules')
            ->latest()
            ->paginate(10);
            
        return view('analisis.index', compact('dataHistori'));
    }

    public function create()
    {
        $angkatans = Mahasiswa::select('angkatan')
            ->whereNotNull('angkatan')
            ->distinct()
            ->orderBy('angkatan', 'desc')
            ->pluck('angkatan');
            
        return view('analisis.create', compact('angkatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'angkatan' => 'required|integer',
            'min_support' => 'required|numeric|min:0.01|max:1',
            'min_confidence' => 'required|numeric|min:0.01|max:1',
            'deskripsi' => 'required|string|max:500'
        ]);

        try {
            // Get mahasiswa data for specified angkatan
            $mahasiswas = Mahasiswa::byAngkatan($request->angkatan)->get();
            
            if ($mahasiswas->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'Tidak ada data mahasiswa untuk angkatan ' . $request->angkatan);
            }

            // Prepare transaction data
            $transactions = $this->prepareTransactionData($mahasiswas);
            
            if (empty($transactions)) {
                return redirect()->back()
                    ->with('error', 'Tidak ada data CPL yang valid untuk dianalisis');
            }

            // Run Apriori algorithm
            $results = $this->runAprioriAlgorithm(
                $transactions, 
                $request->min_support, 
                $request->min_confidence
            );

            // Save to database
            $dataHistori = DataHistori::create([
                'tanggal' => Carbon::today(),
                'angkatan' => $request->angkatan,
                'deskripsi' => $request->deskripsi,
                'hasil_analisis' => $results,
                'min_support' => $request->min_support,
                'min_confidence' => $request->min_confidence,
                'total_rules' => count($results['rules_1to1']) + count($results['rules_2to1']) + count($results['rules_3to1'])
            ]);

            // Save association rules
            $this->saveAssociationRules($dataHistori, $results);

            return redirect()->route('analisis.show', $dataHistori)
                ->with('success', 'Analisis berhasil diselesaikan');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error saat melakukan analisis: ' . $e->getMessage());
        }
    }

    public function show(DataHistori $analisis)
    {
        $dataHistori = $analisis;
        $dataHistori->load('associationRules');
        
        // Check if association rules exist, if not, regenerate them
        if ($dataHistori->associationRules()->count() == 0) {
            // Get data mahasiswa for this analysis
            $mahasiswas = Mahasiswa::where('angkatan', $dataHistori->angkatan)->get();
            
            if ($mahasiswas->count() > 0) {
                // Prepare transaction data
                $transactions = $this->prepareTransactionData($mahasiswas);
                
                // Run Apriori algorithm
                $results = $this->runAprioriAlgorithm(
                    $transactions, 
                    $dataHistori->min_support, 
                    $dataHistori->min_confidence
                );
                
                // Save association rules
                $this->saveAssociationRules($dataHistori, $results);
                
                // Reload the association rules
                $dataHistori->load('associationRules');
            }
        }
        
        // Get top 10 rules sorted by confidence for each type
        $oneToOneRules = $dataHistori->associationRules()
            ->byRuleType('1to1')
            ->orderByConfidence('desc')
            ->take(10)
            ->get();
            
        $twoToOneRules = $dataHistori->associationRules()
            ->byRuleType('2to1')
            ->orderByConfidence('desc')
            ->take(10)
            ->get();
            
        $threeToOneRules = $dataHistori->associationRules()
            ->byRuleType('3to1')
            ->orderByConfidence('desc')
            ->take(10)
            ->get();
        
        $topRules = $dataHistori->associationRules()
            ->orderByConfidence()
            ->take(10)
            ->get();
            
        // Generate profil lulusan recommendations
        $profilLulusanRecommendations = $this->generateProfilLulusanRecommendations($dataHistori);
            
        return view('analisis.show', compact('dataHistori', 'oneToOneRules', 'twoToOneRules', 'threeToOneRules', 'topRules', 'profilLulusanRecommendations'));
    }

    public function destroy(DataHistori $analisis)
    {
        $analisis->delete(); // Will cascade delete association rules
        
        return redirect()->route('analisis.index')
            ->with('success', 'Data analisis berhasil dihapus');
    }

    private function prepareTransactionData($mahasiswas)
    {
        $transactions = [];
        
        foreach ($mahasiswas as $mahasiswa) {
            $transaction = [];
            $categorizedCpl = $mahasiswa->categorized_cpl;
            
            foreach ($categorizedCpl as $cpl => $kategori) {
                if ($kategori !== 'Missing') {
                    $transaction[] = $cpl . '=' . $kategori;
                }
            }
            
            if (!empty($transaction)) {
                $transactions[] = $transaction;
            }
        }
        
        return $transactions;
    }

    private function runAprioriAlgorithm($transactions, $minSupport, $minConfidence)
    {
        // Calculate item frequencies
        $itemCounts = [];
        $totalTransactions = count($transactions);
        
        // Check if we have enough transactions
        if ($totalTransactions == 0) {
            return [
                'item_frequencies' => [],
                'rules_1to1' => [],
                'rules_2to1' => [],
                'rules_3to1' => [],
                'total_transactions' => 0,
                'parameters' => [
                    'min_support' => $minSupport,
                    'min_confidence' => $minConfidence
                ]
            ];
        }
        
        foreach ($transactions as $transaction) {
            foreach ($transaction as $item) {
                $itemCounts[$item] = ($itemCounts[$item] ?? 0) + 1;
            }
        }
        
        // Calculate support for each item
        $itemSupport = [];
        if ($totalTransactions > 0) {
            foreach ($itemCounts as $item => $count) {
                $support = $count / $totalTransactions;
                if ($support >= $minSupport) {
                    $itemSupport[$item] = $support;
                }
            }
        }
        
        // Generate 1-to-1 rules
        $rules1to1 = $this->generate1to1Rules($transactions, $itemSupport, $minConfidence);
        
        // Generate 2-to-1 rules
        $rules2to1 = $this->generate2to1Rules($transactions, $itemSupport, $minSupport, $minConfidence);
        
        // Generate 3-to-1 rules
        $rules3to1 = $this->generate3to1Rules($transactions, $itemSupport, $minSupport, $minConfidence);
        
        return [
            'item_frequencies' => $itemSupport,
            'rules_1to1' => $rules1to1,
            'rules_2to1' => $rules2to1,
            'rules_3to1' => $rules3to1,
            'total_transactions' => $totalTransactions,
            'parameters' => [
                'min_support' => $minSupport,
                'min_confidence' => $minConfidence
            ]
        ];
    }

    private function generate1to1Rules($transactions, $itemSupport, $minConfidence)
    {
        $rules = [];
        $items = array_keys($itemSupport);
        $totalTransactions = count($transactions);
        
        // Check if we have enough data
        if ($totalTransactions == 0 || empty($items)) {
            return $rules;
        }
        
        // Generate all pairs of items
        for ($i = 0; $i < count($items); $i++) {
            for ($j = 0; $j < count($items); $j++) {
                if ($i !== $j) {
                    $itemA = $items[$i];
                    $itemB = $items[$j];
                    
                    // Count co-occurrences
                    $coOccurrence = 0;
                    foreach ($transactions as $transaction) {
                        if (in_array($itemA, $transaction) && in_array($itemB, $transaction)) {
                            $coOccurrence++;
                        }
                    }
                    
                    if ($coOccurrence > 0) {
                        $supportAB = $coOccurrence / $totalTransactions;
                        $supportA = $itemSupport[$itemA] ?? 0;
                        $supportB = $itemSupport[$itemB] ?? 0;
                        
                        // Avoid division by zero
                        if ($supportA > 0 && $supportB > 0) {
                            $confidence = $supportAB / $supportA;
                            $lift = $supportAB / ($supportA * $supportB);
                            
                            if ($confidence >= $minConfidence) {
                                $rules[] = [
                                    'lhs' => [$itemA],
                                    'rhs' => [$itemB],
                                    'support' => $supportAB,
                                    'confidence' => $confidence,
                                    'lift' => $lift,
                                    'rule_type' => '1to1'
                                ];
                            }
                        }
                    }
                }
            }
        }
        
        // Sort by confidence descending
        usort($rules, function($a, $b) {
            return $b['confidence'] <=> $a['confidence'];
        });
        
        return array_slice($rules, 0, 10); // Return top 10
    }

    private function generate2to1Rules($transactions, $itemSupport, $minSupport, $minConfidence)
    {
        $rules = [];
        $items = array_keys($itemSupport);
        $totalTransactions = count($transactions);
        
        // Check if we have enough data
        if ($totalTransactions == 0 || empty($items)) {
            return $rules;
        }
        
        // Generate all combinations of 2 items for LHS
        for ($i = 0; $i < count($items); $i++) {
            for ($j = $i + 1; $j < count($items); $j++) {
                for ($k = 0; $k < count($items); $k++) {
                    if ($k !== $i && $k !== $j) {
                        $itemA = $items[$i];
                        $itemB = $items[$j];
                        $itemC = $items[$k];
                        
                        // Count occurrences of A and B together
                        $countAB = 0;
                        $countABC = 0;
                        
                        foreach ($transactions as $transaction) {
                            $hasAB = in_array($itemA, $transaction) && in_array($itemB, $transaction);
                            $hasABC = $hasAB && in_array($itemC, $transaction);
                            
                            if ($hasAB) $countAB++;
                            if ($hasABC) $countABC++;
                        }
                        
                        if ($countAB > 0 && $countABC > 0) {
                            $supportAB = $countAB / $totalTransactions;
                            $supportABC = $countABC / $totalTransactions;
                            $supportC = $itemSupport[$itemC] ?? 0;
                            
                            if ($supportAB >= $minSupport && $countAB > 0 && $supportC > 0) {
                                $confidence = $countABC / $countAB;
                                $lift = $supportABC / ($supportAB * $supportC);
                                
                                if ($confidence >= $minConfidence) {
                                    $rules[] = [
                                        'lhs' => [$itemA, $itemB],
                                        'rhs' => [$itemC],
                                        'support' => $supportABC,
                                        'confidence' => $confidence,
                                        'lift' => $lift,
                                        'rule_type' => '2to1'
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // Sort by confidence descending
        usort($rules, function($a, $b) {
            return $b['confidence'] <=> $a['confidence'];
        });
        
        return array_slice($rules, 0, 10); // Return top 10
    }

    private function generate3to1Rules($transactions, $itemSupport, $minSupport, $minConfidence)
    {
        $rules = [];
        $items = array_keys($itemSupport);
        $totalTransactions = count($transactions);
        
        // Check if we have enough data
        if ($totalTransactions == 0 || empty($items)) {
            return $rules;
        }
        
        // Generate all combinations of 3 items for LHS
        for ($i = 0; $i < count($items); $i++) {
            for ($j = $i + 1; $j < count($items); $j++) {
                for ($k = $j + 1; $k < count($items); $k++) {
                    for ($l = 0; $l < count($items); $l++) {
                        if ($l !== $i && $l !== $j && $l !== $k) {
                            $itemA = $items[$i];
                            $itemB = $items[$j];
                            $itemC = $items[$k];
                            $itemD = $items[$l];
                            
                            // Count occurrences of A, B, and C together
                            $countABC = 0;
                            $countABCD = 0;
                            
                            foreach ($transactions as $transaction) {
                                $hasABC = in_array($itemA, $transaction) && 
                                         in_array($itemB, $transaction) && 
                                         in_array($itemC, $transaction);
                                $hasABCD = $hasABC && in_array($itemD, $transaction);
                                
                                if ($hasABC) $countABC++;
                                if ($hasABCD) $countABCD++;
                            }
                            
                            if ($countABC > 0 && $countABCD > 0) {
                                $supportABC = $countABC / $totalTransactions;
                                $supportABCD = $countABCD / $totalTransactions;
                                $supportD = $itemSupport[$itemD] ?? 0;
                                
                                if ($supportABC >= $minSupport && $countABC > 0 && $supportD > 0) {
                                    $confidence = $countABCD / $countABC;
                                    $lift = $supportABCD / ($supportABC * $supportD);
                                    
                                    if ($confidence >= $minConfidence) {
                                        $rules[] = [
                                            'lhs' => [$itemA, $itemB, $itemC],
                                            'rhs' => [$itemD],
                                            'support' => $supportABCD,
                                            'confidence' => $confidence,
                                            'lift' => $lift,
                                            'rule_type' => '3to1'
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // Sort by confidence descending
        usort($rules, function($a, $b) {
            return $b['confidence'] <=> $a['confidence'];
        });
        
        return array_slice($rules, 0, 10); // Return top 10
    }

    private function saveAssociationRules($dataHistori, $results)
    {
        $rulesToSave = [];
        
        // Save 1-to-1 rules
        foreach ($results['rules_1to1'] as $rule) {
            $rulesToSave[] = [
                'data_histori_id' => $dataHistori->id,
                'lhs' => json_encode($rule['lhs']),
                'rhs' => json_encode($rule['rhs']),
                'support' => $rule['support'],
                'confidence' => $rule['confidence'],
                'lift' => $rule['lift'],
                'rule_type' => $rule['rule_type'],
                'interpretation' => $this->generateInterpretation($rule),
                'profil' => $this->determineProfilFromRule($rule),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        // Save 2-to-1 rules
        foreach ($results['rules_2to1'] as $rule) {
            $rulesToSave[] = [
                'data_histori_id' => $dataHistori->id,
                'lhs' => json_encode($rule['lhs']),
                'rhs' => json_encode($rule['rhs']),
                'support' => $rule['support'],
                'confidence' => $rule['confidence'],
                'lift' => $rule['lift'],
                'rule_type' => $rule['rule_type'],
                'interpretation' => $this->generateInterpretation($rule),
                'profil' => $this->determineProfilFromRule($rule),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        // Save 3-to-1 rules
        foreach ($results['rules_3to1'] as $rule) {
            $rulesToSave[] = [
                'data_histori_id' => $dataHistori->id,
                'lhs' => json_encode($rule['lhs']),
                'rhs' => json_encode($rule['rhs']),
                'support' => $rule['support'],
                'confidence' => $rule['confidence'],
                'lift' => $rule['lift'],
                'rule_type' => $rule['rule_type'],
                'interpretation' => $this->generateInterpretation($rule),
                'profil' => $this->determineProfilFromRule($rule),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        if (!empty($rulesToSave)) {
            AssociationRule::insert($rulesToSave);
        }
    }

    private function generateInterpretation($rule)
    {
        $lhsStr = implode(' dan ', $rule['lhs']);
        $rhsStr = implode(' dan ', $rule['rhs']);
        
        $interpretation = "Jika {$lhsStr}, maka {$rhsStr} ";
        $interpretation .= "dengan tingkat kepercayaan " . round($rule['confidence'] * 100, 2) . "%. ";
        
        if ($rule['lift'] > 1) {
            $interpretation .= "Asosiasi ini positif dan signifikan (Lift > 1).";
        } elseif ($rule['lift'] < 1) {
            $interpretation .= "Asosiasi ini negatif (Lift < 1).";
        } else {
            $interpretation .= "Tidak ada asosiasi yang signifikan (Lift = 1).";
        }
        
        return $interpretation;
    }

    public function getChartData(Request $request, DataHistori $analisis)
    {
        $type = $request->get('type', 'rules_distribution');
        
        switch ($type) {
            case 'rules_distribution':
                $rules1to1Count = $analisis->associationRules()->byRuleType('1to1')->count();
                $rules2to1Count = $analisis->associationRules()->byRuleType('2to1')->count();
                $rules3to1Count = $analisis->associationRules()->byRuleType('3to1')->count();
                
                return response()->json([
                    '1→1' => $rules1to1Count,
                    '2→1' => $rules2to1Count,
                    '3→1' => $rules3to1Count
                ]);
                
            case 'confidence_distribution':
                $rules = $analisis->associationRules()
                    ->select('confidence', 'rule_type')
                    ->get();
                    
                if ($rules->isEmpty()) {
                    // Return empty data structure for charts
                    return response()->json([
                        '80-100%' => 0,
                        '60-79%' => 0,
                        '40-59%' => 0,
                        '0-39%' => 0
                    ]);
                }
                
                // Group by confidence ranges
                $confidenceRanges = [
                    '80-100%' => 0,
                    '60-79%' => 0,
                    '40-59%' => 0,
                    '0-39%' => 0
                ];
                
                foreach ($rules as $rule) {
                    $confidence = $rule->confidence * 100;
                    if ($confidence >= 80) {
                        $confidenceRanges['80-100%']++;
                    } elseif ($confidence >= 60) {
                        $confidenceRanges['60-79%']++;
                    } elseif ($confidence >= 40) {
                        $confidenceRanges['40-59%']++;
                    } else {
                        $confidenceRanges['0-39%']++;
                    }
                }
                
                return response()->json($confidenceRanges);
                
            case 'lift_vs_confidence':
                $rules = $analisis->associationRules()
                    ->select('confidence', 'lift', 'support', 'rule_type')
                    ->get();
                    
                if ($rules->isEmpty()) {
                    // Return empty array for scatter plot
                    return response()->json([]);
                }
                
                $scatterData = $rules->map(function ($rule) {
                    return [
                        'confidence' => $rule->confidence * 100,
                        'lift' => $rule->lift,
                        'support' => $rule->support * 100,
                        'rule_type' => $rule->rule_type
                    ];
                });
                
                return response()->json($scatterData);
                
            case 'lift_distribution':
                $rules = $analisis->associationRules()
                    ->select('lift')
                    ->get();
                    
                if ($rules->isEmpty()) {
                    // Return empty data structure for lift distribution
                    return response()->json([
                        '>1.5' => 0,
                        '1.1-1.5' => 0,
                        '0.8-1.1' => 0,
                        '<0.8' => 0
                    ]);
                }
                
                // Group by lift ranges
                $liftRanges = [
                    '>1.5' => 0,
                    '1.1-1.5' => 0,
                    '0.8-1.1' => 0,
                    '<0.8' => 0
                ];
                
                foreach ($rules as $rule) {
                    $lift = $rule->lift;
                    if ($lift > 1.5) {
                        $liftRanges['>1.5']++;
                    } elseif ($lift >= 1.1) {
                        $liftRanges['1.1-1.5']++;
                    } elseif ($lift >= 0.8) {
                        $liftRanges['0.8-1.1']++;
                    } else {
                        $liftRanges['<0.8']++;
                    }
                }
                
                return response()->json($liftRanges);
                
            default:
                return response()->json([]);
        }
    }

    private function generateProfilLulusanRecommendations($dataHistori)
    {
        // Define CPL to Profil Lulusan mapping berdasarkan 6 kategori CPL
        $cplMapping = [
            'studi_lanjut' => [
                'cpls' => ['CPL 1', 'CPL 2', 'CPL 3', 'CPL 4'],
                'categories' => [
                    'Kategori 1' => 'Penguasaan dan penerapan ilmu dasar sains dan matematik',
                    'Kategori 2' => 'Kemampuan perumusan solusi permasalahan pada objek Teknik Industri'
                ]
            ],
            'pegawai_profesional' => [
                'cpls' => ['CPL 5', 'CPL 6', 'CPL 7', 'CPL 8', 'CPL 9', 'CPL 10'],
                'categories' => [
                    'Kategori 3' => 'Kemampuan perancangan dan penelitian pada objek sistem integrasi',
                    'Kategori 4' => 'Kemampuan penguasaan teknik umum dan TIK dalam upaya implementasi keilmuaan Teknik Industri'
                ]
            ],
            'kewirausahaan' => [
                'cpls' => ['CPL 11', 'CPL 12', 'CPL 13', 'CPL 14', 'CPL 15', 'CPL 16'],
                'categories' => [
                    'Kategori 5' => 'Penguasaan aspek non-akademis pendukung',
                    'Kategori 6' => 'Penguasaan keilmuaan pendukung kewirausahaan'
                ]
            ]
        ];

        $recommendations = [];
        
        // Analyze association rules for each profil lulusan
        foreach ($cplMapping as $profil => $mapping) {
            $profilScore = 0;
            $relevantRules = [];
            $categoryScores = [];
            
            // Initialize category scores
            foreach ($mapping['categories'] as $categoryKey => $categoryName) {
                $categoryScores[$categoryKey] = [
                    'name' => $categoryName,
                    'score' => 0,
                    'rule_count' => 0,
                    'avg_confidence' => 0
                ];
            }
            
            // Check all association rules
            $allRules = $dataHistori->associationRules;
            
            foreach ($allRules as $rule) {
                $lhs = is_array($rule->lhs) ? $rule->lhs : [$rule->lhs];
                $rhs = is_array($rule->rhs) ? $rule->rhs : [$rule->rhs];
                
                // Extract CPL from rule items
                $ruleCPLs = [];
                foreach (array_merge($lhs, $rhs) as $item) {
                    if (preg_match('/CPL (\d+)=/', $item, $matches)) {
                        $ruleCPLs[] = 'CPL ' . $matches[1];
                    }
                }
                
                // Check if rule is relevant to this profil
                $relevantCPLs = array_intersect($ruleCPLs, $mapping['cpls']);
                
                if (!empty($relevantCPLs)) {
                    $relevantRules[] = $rule;
                    $ruleWeight = $rule->confidence * $rule->lift * $rule->support;
                    $profilScore += $ruleWeight;
                    
                    // Categorize the rule
                    foreach ($mapping['categories'] as $categoryKey => $categoryName) {
                        $categoryRange = $this->getCPLRangeFromKey($categoryKey);
                        $categoryIntersect = array_intersect($relevantCPLs, $categoryRange);
                        
                        if (!empty($categoryIntersect)) {
                            $categoryScores[$categoryKey]['score'] += $ruleWeight;
                            $categoryScores[$categoryKey]['rule_count']++;
                        }
                    }
                }
            }
            
            // Calculate average confidence for each category
            foreach ($categoryScores as $key => &$category) {
                if ($category['rule_count'] > 0) {
                    $totalConfidence = 0;
                    $count = 0;
                    
                    foreach ($relevantRules as $rule) {
                        $lhs = is_array($rule->lhs) ? $rule->lhs : [$rule->lhs];
                        $rhs = is_array($rule->rhs) ? $rule->rhs : [$rule->rhs];
                        
                        $ruleCPLs = [];
                        foreach (array_merge($lhs, $rhs) as $item) {
                            if (preg_match('/CPL (\d+)=/', $item, $matches)) {
                                $ruleCPLs[] = 'CPL ' . $matches[1];
                            }
                        }
                        
                        $categoryRange = $this->getCPLRangeFromKey($key);
                        $categoryIntersect = array_intersect($ruleCPLs, $categoryRange);
                        
                        if (!empty($categoryIntersect)) {
                            $totalConfidence += $rule->confidence;
                            $count++;
                        }
                    }
                    
                    $category['avg_confidence'] = ($count > 0 && $totalConfidence > 0) ? $totalConfidence / $count : 0;
                }
            }
            
            $recommendations[$profil] = [
                'name' => $this->getProfilLulusanName($profil),
                'score' => $profilScore,
                'rule_count' => count($relevantRules),
                'categories' => $categoryScores,
                'relevant_rules' => array_slice($relevantRules, 0, 5) // Top 5 rules
            ];
        }
        
        // Sort by score
        uasort($recommendations, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        return $recommendations;
    }
    
    private function getCPLRangeFromKey($key)
    {
        switch ($key) {
            case 'Kategori 1':
                return ['CPL 1', 'CPL 2'];
            case 'Kategori 2':
                return ['CPL 3', 'CPL 4'];
            case 'Kategori 3':
                return ['CPL 5', 'CPL 6', 'CPL 7'];
            case 'Kategori 4':
                return ['CPL 8', 'CPL 9', 'CPL 10'];
            case 'Kategori 5':
                return ['CPL 11', 'CPL 12', 'CPL 13', 'CPL 14'];
            case 'Kategori 6':
                return ['CPL 15', 'CPL 16'];
            default:
                return [];
        }
    }
    
    private function getProfilLulusanName($profil)
    {
        switch ($profil) {
            case 'studi_lanjut':
                return 'Studi Lanjut';
            case 'pegawai_profesional':
                return 'Pegawai Profesional';
            case 'kewirausahaan':
                return 'Kewirausahaan';
            default:
                return ucfirst(str_replace('_', ' ', $profil));
        }
    }

    private function determineProfilFromRule($rule)
    {
        // Extract CPL numbers from rule
        $allItems = array_merge($rule['lhs'], $rule['rhs']);
        $cplNumbers = [];
        
        foreach ($allItems as $item) {
            if (preg_match('/CPL (\d+)=/', $item, $matches)) {
                $cplNumbers[] = (int)$matches[1];
            }
        }
        
        if (empty($cplNumbers)) {
            return 'Pegawai Profesional'; // Default
        }
        
        // Count CPL distribution
        $studiLanjutCount = 0;
        $pegawaiProfesionalCount = 0;
        $kewirausahaanCount = 0;
        
        foreach ($cplNumbers as $cplNum) {
            if ($cplNum >= 1 && $cplNum <= 4) {
                $studiLanjutCount++;
            } elseif ($cplNum >= 5 && $cplNum <= 10) {
                $pegawaiProfesionalCount++;
            } elseif ($cplNum >= 11 && $cplNum <= 16) {
                $kewirausahaanCount++;
            }
        }
        
        // Determine profil based on majority
        if ($studiLanjutCount >= $pegawaiProfesionalCount && $studiLanjutCount >= $kewirausahaanCount) {
            return 'Studi Lanjut';
        } elseif ($kewirausahaanCount >= $pegawaiProfesionalCount && $kewirausahaanCount >= $studiLanjutCount) {
            return 'Kewirausahaan';
        } else {
            return 'Pegawai Profesional';
        }
    }

    private function sortRulesByCPL($rules)
    {
        return $rules->sort(function ($a, $b) {
            // Extract CPL numbers from LHS and RHS
            $aCPLs = $this->extractCPLNumbers($a);
            $bCPLs = $this->extractCPLNumbers($b);
            
            // Compare by first CPL number
            $aFirstCPL = !empty($aCPLs) ? min($aCPLs) : 999;
            $bFirstCPL = !empty($bCPLs) ? min($bCPLs) : 999;
            
            return $aFirstCPL <=> $bFirstCPL;
        });
    }

    private function extractCPLNumbers($rule)
    {
        $cplNumbers = [];
        $allItems = [];
        
        // Handle LHS
        if (is_array($rule->lhs)) {
            $allItems = array_merge($allItems, $rule->lhs);
        } else {
            $lhsDecoded = json_decode($rule->lhs, true);
            if (is_array($lhsDecoded)) {
                $allItems = array_merge($allItems, $lhsDecoded);
            } else {
                $allItems[] = $rule->lhs;
            }
        }
        
        // Handle RHS
        if (is_array($rule->rhs)) {
            $allItems = array_merge($allItems, $rule->rhs);
        } else {
            $rhsDecoded = json_decode($rule->rhs, true);
            if (is_array($rhsDecoded)) {
                $allItems = array_merge($allItems, $rhsDecoded);
            } else {
                $allItems[] = $rule->rhs;
            }
        }
        
        // Extract CPL numbers
        foreach ($allItems as $item) {
            if (preg_match('/CPL (\d+)=/', $item, $matches)) {
                $cplNumbers[] = (int)$matches[1];
            }
        }
        
        return $cplNumbers;
    }
}
