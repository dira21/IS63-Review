<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    /**
     * index() — Tampilkan daftar program studi
     * GET /prodi
     */
    public function index()
    {
        $prodis = Prodi::withCount('mahasiswas')
            ->orderBy('nama_prodi')
            ->paginate(10);

        return view('prodi.index', compact('prodis'));
    }

    /**
     * create() — Tampilkan form tambah prodi
     * GET /prodi/create
     */
    public function create()
    {
        return view('prodi.create');
    }

    /**
     * store() — Simpan prodi baru ke database
     * POST /prodi
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'kode_prodi' => 'required|string|max:10|unique:prodis,kode_prodi',
            'nama_prodi' => 'required|string|max:100',
            'jenjang' => 'required|in:D3,S1,S2,S3',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            // Pesan error kustom (opsional)
            'kode_prodi.required' => 'Kode prodi wajib diisi.',
            'kode_prodi.unique' => 'Kode prodi sudah digunakan.',
            'nama_prodi.required' => 'Nama program studi wajib diisi.',
            'jenjang.in' => 'Jenjang harus D3, S1, S2, atau S3.',
        ]);

        Prodi::create($validated);

        return redirect()
            ->route('prodi.index')
            ->with('success', 'Program studi berhasil ditambahkan!');
    }

    /**
     * show() — Tampilkan detail prodi beserta daftar mahasiswanya
     * GET /prodi/{prodi}
     */
    public function show(Prodi $prodi)
    {
        // Eager load mahasiswa dengan pagination
        $mahasiswas = $prodi->mahasiswas()
            ->orderBy('nama')
            ->paginate(10);

        return view('prodi.show', compact('prodi', 'mahasiswas'));
    }

    /**
     * edit() — Tampilkan form edit prodi
     * GET /prodi/{prodi}/edit
     */
    public function edit(Prodi $prodi)
    {
        return view('prodi.edit', compact('prodi'));
    }

    /**
     * update() — Perbarui data prodi
     * PUT /prodi/{prodi}
     */
    public function update(Request $request, Prodi $prodi)
    {
        $validated = $request->validate([
            // unique kecuali record prodi ini sendiri (ignore ID saat ini)
            'kode_prodi' => 'required|string|max:10|unique:prodis,kode_prodi,' . $prodi->id,
            'nama_prodi' => 'required|string|max:100',
            'jenjang' => 'required|in:D3,S1,S2,S3',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $prodi->update($validated);

        return redirect()
            ->route('prodi.index')
            ->with('success', 'Program studi berhasil diperbarui!');
    }

    /**
     * destroy() — Hapus data prodi
     * DELETE /prodi/{prodi}
     */
    public function destroy(Prodi $prodi)
    {
        // Cegah hapus jika masih ada mahasiswa terdaftar
        if ($prodi->mahasiswas()->count() > 0) {
            return redirect()
                ->route('prodi.index')
                ->with('error', 'Prodi tidak bisa dihapus karena masih memiliki mahasiswa!');
        }

        $prodi->delete();

        return redirect()
            ->route('prodi.index')
            ->with('success', 'Program studi berhasil dihapus!');
    }
}