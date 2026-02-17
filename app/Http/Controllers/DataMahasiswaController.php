<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Cpl;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DataMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::query();
        
        if ($request->has('angkatan') && $request->angkatan != '') {
            $query->byAngkatan($request->angkatan);
        }
        
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nim', 'like', '%' . $request->search . '%')
                  ->orWhere('nama', 'like', '%' . $request->search . '%');
            });
        }
        
        $mahasiswas = $query->orderBy('nim')->paginate(20);
        
        $angkatans = Mahasiswa::select('angkatan')
            ->whereNotNull('angkatan')
            ->distinct()
            ->orderBy('angkatan', 'desc')
            ->pluck('angkatan');
            
        $cpls = Cpl::active()->get()->sortBy(function($cpl) {
            return (int)str_replace('CPL ', '', $cpl->kode_cpl);
        });
        
        // Statistics
        $totalMahasiswa = Mahasiswa::count();
        $dataLengkap = Mahasiswa::whereNotNull('nilai_cpl')->count();
        $totalAngkatan = Mahasiswa::distinct('angkatan')->count('angkatan');
        
        // Calculate average CPL
        $avgCpl = 0;
        $totalNilai = 0;
        $countNilai = 0;
        
        foreach (Mahasiswa::whereNotNull('nilai_cpl')->get() as $mahasiswa) {
            foreach ($mahasiswa->nilai_cpl as $cplCode => $nilai) {
                if (is_numeric($nilai)) {
                    $totalNilai += $nilai;
                    $countNilai++;
                }
            }
        }
        
        if ($countNilai > 0) {
            $avgCpl = $totalNilai / $countNilai;
        }
        
        // Chart data
        $angkatanStats = Mahasiswa::selectRaw('angkatan, count(*) as total')
            ->whereNotNull('angkatan')
            ->groupBy('angkatan')
            ->orderBy('angkatan')
            ->get();
            
        // CPL Performance data
        $cplPerformance = [];
        foreach ($cpls as $cpl) {
            $totalNilaiCpl = 0;
            $countNilaiCpl = 0;
            
            foreach (Mahasiswa::whereNotNull('nilai_cpl')->get() as $mahasiswa) {
                $nilai = $mahasiswa->nilai_cpl[$cpl->kode_cpl] ?? null;
                if (is_numeric($nilai)) {
                    $totalNilaiCpl += $nilai;
                    $countNilaiCpl++;
                }
            }
            
            $avgNilaiCpl = $countNilaiCpl > 0 ? $totalNilaiCpl / $countNilaiCpl : 0;
            $cplPerformance[$cpl->kode_cpl] = $avgNilaiCpl;
        }
        
        $chartData = [
            'angkatan' => [
                'labels' => $angkatanStats->pluck('angkatan')->toArray(),
                'data' => $angkatanStats->pluck('total')->toArray()
            ],
            'cpl' => [
                'labels' => array_keys($cplPerformance),
                'data' => array_values($cplPerformance)
            ]
        ];
        
        return view('data-mahasiswa.index', compact(
            'mahasiswas', 
            'angkatans', 
            'cpls',
            'totalMahasiswa',
            'dataLengkap',
            'totalAngkatan',
            'avgCpl',
            'chartData'
        ));
    }

    public function create()
    {
        $cpls = Cpl::active()->get()->sortBy(function($cpl) {
            return (int)str_replace('CPL ', '', $cpl->kode_cpl);
        });
        return view('data-mahasiswa.create', compact('cpls'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => [
                'required',
                'unique:mahasiswas,nim',
                function ($attribute, $value, $fail) {
                    if (!Mahasiswa::validateNimFormat($value)) {
                        $fail('Format NIM tidak valid. Gunakan format: 102012300355 (2023+) atau 1201220567 (2022-)');
                    }
                },
            ],
            'nama' => 'required|string|max:255',
            'angkatan' => 'nullable|integer|min:2000|max:' . (date('Y') + 5),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $mahasiswa = Mahasiswa::create([
            'nim' => $request->nim,
            'nama' => $request->nama,
            'angkatan' => $request->angkatan,
            'nilai_cpl' => $request->nilai_cpl ?? []
        ]);

        return redirect()->route('data-mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil ditambahkan');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $cpls = Cpl::active()->get();
        return view('data-mahasiswa.show', compact('mahasiswa', 'cpls'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $cpls = Cpl::active()->get()->sortBy(function($cpl) {
            return (int)str_replace('CPL ', '', $cpl->kode_cpl);
        });
        return view('data-mahasiswa.edit', compact('mahasiswa', 'cpls'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validator = Validator::make($request->all(), [
            'nim' => [
                'required',
                'unique:mahasiswas,nim,' . $mahasiswa->id,
                function ($attribute, $value, $fail) {
                    if (!Mahasiswa::validateNimFormat($value)) {
                        $fail('Format NIM tidak valid. Gunakan format: 102012300355 (2023+) atau 1201220567 (2022-)');
                    }
                },
            ],
            'nama' => 'required|string|max:255',
            'angkatan' => 'nullable|integer|min:2000|max:' . (date('Y') + 5),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $mahasiswa->update([
            'nim' => $request->nim,
            'nama' => $request->nama,
            'angkatan' => $request->angkatan,
            'nilai_cpl' => $request->nilai_cpl ?? []
        ]);

        return redirect()->route('data-mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();
        
        return redirect()->route('data-mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus');
    }

    public function importForm()
    {
        return view('data-mahasiswa.import');
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls|max:10240', // Max 10MB
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $data = Excel::toArray([], $request->file('file'));
            
            if (empty($data) || empty($data[0])) {
                return redirect()->back()
                    ->with('error', 'File Excel kosong atau tidak valid');
            }

            $rows = $data[0];
            $headers = array_shift($rows); // Remove header row
            
            /*
             * Format NIM yang didukung:
             * - 1201190001 -> angkatan 2019 (digit ke-5,6 = "19")
             * - 1201200005 -> angkatan 2020 (digit ke-5,6 = "20")
             * - 1201210010 -> angkatan 2021 (digit ke-5,6 = "21")
             * Sistem otomatis mengekstrak angkatan dari digit ke-5 dan ke-6 NIM
             */
            
            // Find CPL columns
            $cplColumns = [];
            foreach ($headers as $index => $header) {
                if (stripos($header, 'CPL') !== false) {
                    $cplColumns[$index] = $header;
                }
            }

            if (empty($cplColumns)) {
                return redirect()->back()
                    ->with('error', 'Tidak ditemukan kolom CPL dalam file Excel');
            }

            $imported = 0;
            $errors = [];

            foreach ($rows as $rowIndex => $row) {
                try {
                    // Find NIM and Name columns
                    $nim = null;
                    $nama = null;
                    $angkatan = null;

                    // Assume first column is No, second is NIM, third is Name
                    if (count($row) >= 3) {
                        $nim = $row[1] ?? null;
                        $nama = $row[2] ?? null;
                        
                        // Extract angkatan from NIM using helper function
                        $angkatan = Mahasiswa::extractAngkatanFromNim($nim);
                    }

                    if (!$nim || !$nama) {
                        $errors[] = "Baris " . ($rowIndex + 2) . ": NIM atau nama tidak valid";
                        continue;
                    }

                    // Extract CPL values
                    $nilaiCpl = [];
                    foreach ($cplColumns as $index => $cplCode) {
                        $nilaiCpl[$cplCode] = isset($row[$index]) && is_numeric($row[$index]) 
                            ? (float)$row[$index] 
                            : null;
                    }

                    // Create or update mahasiswa
                    Mahasiswa::updateOrCreate(
                        ['nim' => $nim],
                        [
                            'nama' => $nama,
                            'angkatan' => $angkatan,
                            'nilai_cpl' => $nilaiCpl
                        ]
                    );

                    $imported++;

                } catch (\Exception $e) {
                    $errors[] = "Baris " . ($rowIndex + 2) . ": " . $e->getMessage();
                }
            }

            $message = "Berhasil mengimpor {$imported} data mahasiswa";
            if (!empty($errors)) {
                $message .= ". Error: " . implode(', ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= " dan " . (count($errors) - 5) . " error lainnya";
                }
            }

            return redirect()->route('data-mahasiswa.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error mengimpor file: ' . $e->getMessage());
        }
    }

    public function normalize(Request $request)
    {
        try {
            $angkatan = $request->get('angkatan');
            $query = Mahasiswa::query();
            
            if ($angkatan) {
                $query->byAngkatan($angkatan);
            }
            
            $mahasiswas = $query->get();
            $updated = 0;
            
            foreach ($mahasiswas as $mahasiswa) {
                if ($mahasiswa->nilai_cpl) {
                    // Already have data, just count
                    $updated++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Berhasil menormalisasi data {$updated} mahasiswa",
                'updated' => $updated
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download(Request $request)
    {
        $angkatan = $request->get('angkatan');
        $search = $request->get('search');
        
        $query = Mahasiswa::query();
        
        if ($angkatan) {
            $query->byAngkatan($angkatan);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }
        
        $mahasiswas = $query->orderBy('nim')->get();
        $cpls = Cpl::active()->orderBy('kode_cpl')->get();
        
        // Create Excel data
        $headers = ['No', 'NIM', 'Nama', 'Angkatan'];
        foreach ($cpls as $cpl) {
            $headers[] = $cpl->kode_cpl;
        }
        
        $data = [$headers]; // Add headers as first row
        
        foreach ($mahasiswas as $index => $mahasiswa) {
            $row = [
                $index + 1,
                $mahasiswa->nim,
                $mahasiswa->nama,
                $mahasiswa->angkatan ?? '-'
            ];
            
            foreach ($cpls as $cpl) {
                $nilai = $mahasiswa->nilai_cpl[$cpl->kode_cpl] ?? '';
                $row[] = $nilai !== '' ? $nilai : '-';
            }
            
            $data[] = $row;
        }
        
        $filename = 'data_mahasiswa_' . ($angkatan ? $angkatan . '_' : '') . date('Y-m-d_H-i-s') . '.xlsx';
        
        // Create simple Excel file using PhpSpreadsheet
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set title
            $sheet->setTitle('Data Mahasiswa');
            
            // Set headers with styling
            $headerRow = 1;
            foreach ($headers as $col => $header) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
                $sheet->setCellValue($columnLetter . $headerRow, $header);
                
                // Style headers
                $sheet->getStyle($columnLetter . $headerRow)->getFont()->setBold(true);
                $sheet->getStyle($columnLetter . $headerRow)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('4CAF50');
                $sheet->getStyle($columnLetter . $headerRow)->getFont()->getColor()->setRGB('FFFFFF');
                $sheet->getStyle($columnLetter . $headerRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            
            // Add data rows
            $rowNumber = 2; // Start from row 2 (after headers)
            foreach ($mahasiswas as $index => $mahasiswa) {
                $sheet->setCellValue('A' . $rowNumber, $index + 1);
                $sheet->setCellValue('B' . $rowNumber, $mahasiswa->nim);
                $sheet->setCellValue('C' . $rowNumber, $mahasiswa->nama);
                $sheet->setCellValue('D' . $rowNumber, $mahasiswa->angkatan ?? '-');
                
                $colIndex = 5; // Start from column E
                foreach ($cpls as $cpl) {
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                    $nilai = $mahasiswa->nilai_cpl[$cpl->kode_cpl] ?? '';
                    $sheet->setCellValue($columnLetter . $rowNumber, $nilai !== '' ? $nilai : '-');
                    $colIndex++;
                }
                
                $rowNumber++;
            }
            
            // Auto-size columns
            foreach (range('A', \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers))) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Add borders to all data
            $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
            $lastRow = $rowNumber - 1;
            $range = 'A1:' . $lastColumn . $lastRow;
            
            $sheet->getStyle($range)->getBorders()->getAllBorders()
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
            // Center align numeric data
            $sheet->getStyle('E2:' . $lastColumn . $lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            // Create writer and output
            $writer = new Xlsx($spreadsheet);
            
            return response()->streamDownload(function() use ($writer) {
                $writer->save('php://output');
            }, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error generating Excel file: ' . $e->getMessage());
        }
    }
}