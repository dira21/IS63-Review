<?php
 
namespace App\Http\Controllers;
 
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMahasiswaRequest;    // ← import Form Request
use App\Http\Requests\UpdateMahasiswaRequest; 
use Illuminate\Support\Facades\Storage;
 
class MahasiswaController extends Controller
{
    /**
     * index() — Daftar mahasiswa dengan filter dan pencarian
     * GET /mahasiswa
     */
    public function index(Request $request)
    {
        $query = Mahasiswa::with('prodi');
 
        // Filter pencarian nama atau NIM
        if ($request->filled('search')) {
            $query->cari($request->search);   // menggunakan scope dari Bab 4
        }
 
        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
 
        // Filter prodi
        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->prodi_id);
        }
 
        // Filter angkatan
        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }
 
        $mahasiswas = $query->orderBy('nama')->paginate(10)->withQueryString();
        $prodis     = Prodi::orderBy('nama_prodi')->get();
 
        return view('mahasiswa.index', compact('mahasiswas', 'prodis'));
    }
 
    /**
     * create() — Form tambah mahasiswa
     * GET /mahasiswa/create
     */
    public function create()
    {
        $prodis = Prodi::where('status', 'aktif')->orderBy('nama_prodi')->get();
        return view('mahasiswa.create', compact('prodis'));
    }
 
    /**
     * store() — Simpan mahasiswa baru
     * POST /mahasiswa
     */
    public function store(StoreMahasiswaRequest $request)
    {
        // $request->validated() hanya berisi data yang sudah divalidasi
        $data = $request->validated();
 
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-mahasiswa', 'public');
        }
 
        Mahasiswa::create($data);
 
        return redirect()->route('mahasiswa.index')
                         ->with('success', 'Data mahasiswa berhasil ditambahkan!');
    }
 
    /**
     * show() — Detail mahasiswa beserta nilai-nilainya
     * GET /mahasiswa/{mahasiswa}
     */
    public function show(Mahasiswa $mahasiswa)
    {
        // Eager load relasi yang dibutuhkan di halaman detail
        $mahasiswa->load(['prodi', 'nilais' => function ($q) {
            $q->orderBy('tahun_akademik', 'desc')->orderBy('semester');
        }]);
 
        return view('mahasiswa.show', compact('mahasiswa'));
    }
 
    /**
     * edit() — Form edit mahasiswa
     * GET /mahasiswa/{mahasiswa}/edit
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        $prodis = Prodi::where('status', 'aktif')->orderBy('nama_prodi')->get();
        return view('mahasiswa.edit', compact('mahasiswa', 'prodis'));
    }
 
    /**
     * update() — Perbarui data mahasiswa
     * PUT /mahasiswa/{mahasiswa}
     */
    public function update(UpdateMahasiswaRequest $request, Mahasiswa $mahasiswa)
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($mahasiswa->foto) {
                Storage::disk('public')->delete($mahasiswa->foto);
            }

            $data['foto'] = $request->file('foto')
                ->store('foto-mahasiswa', 'public');
        }

        $mahasiswa->update($data);

        return redirect()
            ->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui!');
    }

    /**
     * destroy() — Hapus data mahasiswa
     * DELETE /mahasiswa/{mahasiswa}
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        // Hapus foto dari storage jika ada
        if ($mahasiswa->foto) {
            Storage::disk('public')->delete($mahasiswa->foto);
        }
 
        // Data nilai akan ikut terhapus (cascade di migration)
        $mahasiswa->delete();
 
        return redirect()
            ->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus!');
    }
}