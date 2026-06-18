<?php
 
namespace App\Http\Controllers;
 
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'prodi_id' => 'required|exists:prodis,id',
            'nim'      => 'required|string|max:20|unique:mahasiswas,nim',
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|max:100|unique:mahasiswas,email',
            'angkatan' => 'required|digits:4|integer|min:2000|max:'.date('Y'),
            'status'   => 'required|in:aktif,cuti,lulus,dropout',
            'no_hp'    => 'nullable|string|max:15',
            'alamat'   => 'nullable|string',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // max 2MB
        ], [
            'prodi_id.exists'  => 'Program studi tidak valid.',
            'nim.unique'       => 'NIM sudah terdaftar.',
            'email.unique'     => 'Email sudah digunakan.',
            'angkatan.digits'  => 'Angkatan harus 4 digit (contoh: 2022).',
            'foto.image'       => 'File harus berupa gambar.',
            'foto.max'         => 'Ukuran foto maksimal 2MB.',
        ]);
 
        // Handle upload foto jika ada
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')
                ->store('foto-mahasiswa', 'public');
        }
 
        Mahasiswa::create($validated);
 
        return redirect()
            ->route('mahasiswa.index')
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
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validated = $request->validate([
            'prodi_id' => 'required|exists:prodis,id',
            'nim'      => 'required|string|max:20|unique:mahasiswas,nim,'.$mahasiswa->id,
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|max:100|unique:mahasiswas,email,'.$mahasiswa->id,
            'angkatan' => 'required|digits:4|integer|min:2000|max:'.date('Y'),
            'status'   => 'required|in:aktif,cuti,lulus,dropout',
            'no_hp'    => 'nullable|string|max:15',
            'alamat'   => 'nullable|string',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
 
        // Handle upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($mahasiswa->foto) {
                Storage::disk('public')->delete($mahasiswa->foto);
            }
            $validated['foto'] = $request->file('foto')
                ->store('foto-mahasiswa', 'public');
        }
 
        $mahasiswa->update($validated);
 
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