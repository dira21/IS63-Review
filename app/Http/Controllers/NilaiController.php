<?php
 
namespace App\Http\Controllers;
 
use App\Models\Nilai;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;
 
class NilaiController extends Controller
{
    /**
     * index() — Daftar semua nilai dengan filter
     * GET /nilai
     */
    public function index(Request $request)
    {
        $query = Nilai::with(['mahasiswa.prodi']);
 
        // Filter berdasarkan mahasiswa
        if ($request->filled('mahasiswa_id')) {
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }
 
        // Filter berdasarkan semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }
 
        // Filter berdasarkan tahun akademik
        if ($request->filled('tahun_akademik')) {
            $query->where('tahun_akademik', $request->tahun_akademik);
        }
 
        // Filter berdasarkan matakuliah
        if ($request->filled('kode_mk')) {
            $query->where('kode_mk', $request->kode_mk);
        }
 
        $nilais      = $query->orderBy('tahun_akademik', 'desc')
                             ->orderBy('semester')
                             ->paginate(15)
                             ->withQueryString();
 
        $mahasiswas  = Mahasiswa::orderBy('nama')->get();
        $matakuliahs = Nilai::select('kode_mk', 'nama_mk')->distinct()->orderBy('nama_mk')->get();
 
        return view('nilai.index', compact('nilais', 'mahasiswas', 'matakuliahs'));
    }
 
    /**
     * create() — Form tambah nilai
     * GET /nilai/create
     * Bisa menerima query param ?mahasiswa_id=X untuk pre-fill mahasiswa
     */
    public function create(Request $request)
    {
        $mahasiswas = Mahasiswa::with('prodi')
                               ->orderBy('nama')
                               ->get();
 
        // Pre-select mahasiswa jika dikirim via query string
        $selectedMahasiswa = $request->filled('mahasiswa_id')
            ? Mahasiswa::find($request->mahasiswa_id)
            : null;
 
        $matakuliahs = $this->daftarMatakuliah();
 
        return view('nilai.create', compact('mahasiswas', 'selectedMahasiswa', 'matakuliahs'));
    }
 
    /**
     * store() — Simpan nilai baru
     * POST /nilai
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mahasiswa_id'   => 'required|exists:mahasiswas,id',
            'kode_mk'        => 'required|string|max:10',
            'nama_mk'        => 'required|string|max:100',
            'sks'            => 'required|integer|min:1|max:6',
            'nilai_angka'    => 'required|numeric|min:0|max:100',
            'semester'       => 'required|in:Ganjil,Genap',
            'tahun_akademik' => 'required|digits:4|integer|min:2000|max:'.date('Y'),
        ]);
 
        // Cegah duplikat: mahasiswa tidak boleh punya nilai matakuliah
        // yang sama di semester dan tahun akademik yang sama
        $duplikat = Nilai::where('mahasiswa_id',   $validated['mahasiswa_id'])
                         ->where('kode_mk',         $validated['kode_mk'])
                         ->where('semester',         $validated['semester'])
                         ->where('tahun_akademik',   $validated['tahun_akademik'])
                         ->exists();
 
        if ($duplikat) {
            return back()
                ->withInput()
                ->withErrors([
                    'kode_mk' => 'Mahasiswa ini sudah memiliki nilai untuk matakuliah yang sama di semester dan tahun akademik yang sama.'
                ]);
        }
 
        // Konversi nilai angka ke huruf secara otomatis
        $validated['nilai_huruf'] = Nilai::konversiHuruf($validated['nilai_angka']);
 
        Nilai::create($validated);
 
        return redirect()
            ->route('nilai.index')
            ->with('success', 'Nilai berhasil ditambahkan!');
    }
 
    /**
     * show() — Detail nilai (jarang digunakan, redirect ke mahasiswa)
     * GET /nilai/{nilai}
     */
    public function show(Nilai $nilai)
    {
        $nilai->load('mahasiswa.prodi');
        return view('nilai.show', compact('nilai'));
    }
 
    /**
     * edit() — Form edit nilai
     * GET /nilai/{nilai}/edit
     */
    public function edit(Nilai $nilai)
    {
        $mahasiswas  = Mahasiswa::with('prodi')->orderBy('nama')->get();
        $matakuliahs = $this->daftarMatakuliah();
        $nilai->load('mahasiswa');
 
        return view('nilai.edit', compact('nilai', 'mahasiswas', 'matakuliahs'));
    }
 
    /**
     * update() — Perbarui nilai
     * PUT /nilai/{nilai}
     */
    public function update(Request $request, Nilai $nilai)
    {
        $validated = $request->validate([
            'mahasiswa_id'   => 'required|exists:mahasiswas,id',
            'kode_mk'        => 'required|string|max:10',
            'nama_mk'        => 'required|string|max:100',
            'sks'            => 'required|integer|min:1|max:6',
            'nilai_angka'    => 'required|numeric|min:0|max:100',
            'semester'       => 'required|in:Ganjil,Genap',
            'tahun_akademik' => 'required|digits:4|integer|min:2000|max:'.date('Y'),
        ]);
 
        // Cegah duplikat kecuali record ini sendiri
        $duplikat = Nilai::where('mahasiswa_id',   $validated['mahasiswa_id'])
                         ->where('kode_mk',         $validated['kode_mk'])
                         ->where('semester',         $validated['semester'])
                         ->where('tahun_akademik',   $validated['tahun_akademik'])
                         ->where('id', '!=', $nilai->id)   // kecualikan record ini
                         ->exists();
 
        if ($duplikat) {
            return back()
                ->withInput()
                ->withErrors(['kode_mk' => 'Duplikat nilai untuk matakuliah, semester, dan tahun akademik yang sama.']);
        }
 
        // Konversi ulang nilai huruf
        $validated['nilai_huruf'] = Nilai::konversiHuruf($validated['nilai_angka']);
 
        $nilai->update($validated);
 
        return redirect()
            ->route('nilai.index')
            ->with('success', 'Nilai berhasil diperbarui!');
    }
 
    /**
     * destroy() — Hapus nilai
     * DELETE /nilai/{nilai}
     */
    public function destroy(Nilai $nilai)
    {
        $mahasiswaId = $nilai->mahasiswa_id;
        $nilai->delete();
 
        return redirect()
            ->route('mahasiswa.show', $mahasiswaId)
            ->with('success', 'Nilai berhasil dihapus!');
    }
 
    /**
     * Helper: Daftar matakuliah tersedia (untuk dropdown form)
     */
    private function daftarMatakuliah(): array
    {
        return [
            ['kode' => 'MK001', 'nama' => 'Pemrograman Web',          'sks' => 3],
            ['kode' => 'MK002', 'nama' => 'Basis Data',               'sks' => 3],
            ['kode' => 'MK003', 'nama' => 'Algoritma & Pemrograman',  'sks' => 4],
            ['kode' => 'MK004', 'nama' => 'Jaringan Komputer',        'sks' => 3],
            ['kode' => 'MK005', 'nama' => 'Rekayasa Perangkat Lunak', 'sks' => 3],
            ['kode' => 'MK006', 'nama' => 'Kecerdasan Buatan',        'sks' => 3],
            ['kode' => 'MK007', 'nama' => 'Sistem Operasi',           'sks' => 3],
            ['kode' => 'MK008', 'nama' => 'Matematika Diskrit',       'sks' => 3],
        ];
    }
}
