<?php

// Menentukan namespace agar class berada pada lokasi yang tepat dalam aplikasi.
namespace App\Http\Controllers;

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use App\Models\Pengguna;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Http\Request;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Support\Facades\Schema;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Validation\Rule;

// Mendefinisikan class sebagai wadah logika pada file ini.
class ManajemenPenggunaController extends Controller
// Membuka blok kode.
{
    // Mendefinisikan method index untuk menjalankan proses tertentu.
    public function index(Request $request)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $hasStatusColumn = Schema::hasColumn('users', 'status');
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $hasUsernameColumn = Schema::hasColumn('users', 'username');

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate([
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'q' => ['nullable', 'string', 'max:100'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'role' => ['nullable', 'in:owner,admin,kasir'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'status' => ['nullable', 'in:aktif,nonaktif'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ]);

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $usersQuery = Pengguna::query();

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['q'])) {
            // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
            $search = trim($validated['q']);

            // Menambahkan kondisi filter pada query data.
            $usersQuery->where(function ($query) use ($search, $hasUsernameColumn) {
                // Menambahkan kondisi filter pada query data.
                $query->where('name', 'like', '%'.$search.'%')
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    ->orWhere('email', 'like', '%'.$search.'%');

                // Memeriksa kondisi untuk menentukan alur proses berikutnya.
                if ($hasUsernameColumn) {
                    // Baris ini merupakan bagian dari logika proses pada file ini.
                    $query->orWhere('username', 'like', '%'.$search.'%');
                // Menutup blok kode.
                }
            // Menutup struktur atau rangkaian proses pada blok sebelumnya.
            });
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['role'])) {
            // Menambahkan kondisi filter pada query data.
            $usersQuery->where('role', $validated['role']);
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['status']) && $hasStatusColumn) {
            // Menambahkan kondisi filter pada query data.
            $usersQuery->where('status', $validated['status']);
        // Menutup blok kode.
        }

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $users = $usersQuery
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('role')
            // Mengatur urutan data agar tampil lebih terstruktur.
            ->orderBy('name')
            // Menjalankan query dan mengambil seluruh data hasilnya.
            ->get();

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $filters = [
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'q' => $validated['q'] ?? null,
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'role' => $validated['role'] ?? null,
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'status' => $validated['status'] ?? null,
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ];

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('pengguna.index', compact('users', 'filters', 'hasStatusColumn', 'hasUsernameColumn'));
    // Menutup blok kode.
    }

    // Mendefinisikan method create untuk menjalankan proses tertentu.
    public function create()
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $hasStatusColumn = Schema::hasColumn('users', 'status');
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $hasUsernameColumn = Schema::hasColumn('users', 'username');

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('pengguna.create', compact('hasStatusColumn', 'hasUsernameColumn'));
    // Menutup blok kode.
    }

    // Mendefinisikan method store untuk menjalankan proses tertentu.
    public function store(Request $request)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $rules = [
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'name' => ['required', 'string', 'max:100'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'role' => ['required', 'in:owner,admin,kasir'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ];

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (Schema::hasColumn('users', 'username')) {
            // Baris ini merupakan bagian dari logika proses pada file ini.
            $rules['username'] = ['nullable', 'string', 'max:50', 'unique:users,username'];
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (Schema::hasColumn('users', 'status')) {
            // Baris ini merupakan bagian dari logika proses pada file ini.
            $rules['status'] = ['required', 'in:aktif,nonaktif'];
        // Menutup blok kode.
        }

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate($rules);

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $payload = [
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'name' => $validated['name'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'email' => $validated['email'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'role' => $validated['role'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'password' => $validated['password'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ];

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (Schema::hasColumn('users', 'nama')) {
            // Baris ini merupakan bagian dari logika proses pada file ini.
            $payload['nama'] = $validated['name'];
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (Schema::hasColumn('users', 'username')) {
            // Baris ini merupakan bagian dari logika proses pada file ini.
            $payload['username'] = $validated['username'] ?? (strstr($validated['email'], '@', true) ?: $validated['email']);
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (Schema::hasColumn('users', 'status')) {
            // Baris ini merupakan bagian dari logika proses pada file ini.
            $payload['status'] = $validated['status'];
        // Menutup blok kode.
        }

        // Menyimpan data baru ke database melalui model yang terkait.
        Pengguna::create($payload);

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('users.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'User berhasil ditambahkan.');
    // Menutup blok kode.
    }

    // Mendefinisikan method edit untuk menjalankan proses tertentu.
    public function edit(Pengguna $user)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $hasStatusColumn = Schema::hasColumn('users', 'status');
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $hasUsernameColumn = Schema::hasColumn('users', 'username');

        // Mengembalikan view beserta data yang diperlukan untuk ditampilkan.
        return view('pengguna.edit', compact('user', 'hasStatusColumn', 'hasUsernameColumn'));
    // Menutup blok kode.
    }

    // Mendefinisikan method update untuk menjalankan proses tertentu.
    public function update(Request $request, Pengguna $user)
    // Membuka blok kode.
    {
        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $rules = [
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'name' => ['required', 'string', 'max:100'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'role' => ['required', 'in:owner,admin,kasir'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ];

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (Schema::hasColumn('users', 'username')) {
            // Baris ini merupakan bagian dari logika proses pada file ini.
            $rules['username'] = ['nullable', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)];
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (Schema::hasColumn('users', 'status')) {
            // Baris ini merupakan bagian dari logika proses pada file ini.
            $rules['status'] = ['required', 'in:aktif,nonaktif'];
        // Menutup blok kode.
        }

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $validated = $request->validate($rules);

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $payload = [
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'name' => $validated['name'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'email' => $validated['email'],
            // Baris ini merupakan bagian dari logika proses pada file ini.
            'role' => $validated['role'],
        // Menandai bagian dari struktur array yang digunakan pada proses ini.
        ];

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! empty($validated['password'])) {
            // Baris ini merupakan bagian dari logika proses pada file ini.
            $payload['password'] = $validated['password'];
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (Schema::hasColumn('users', 'nama')) {
            // Baris ini merupakan bagian dari logika proses pada file ini.
            $payload['nama'] = $validated['name'];
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (Schema::hasColumn('users', 'username')) {
            // Baris ini merupakan bagian dari logika proses pada file ini.
            $payload['username'] = $validated['username'] ?? (strstr($validated['email'], '@', true) ?: $validated['email']);
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (Schema::hasColumn('users', 'status')) {
            // Baris ini merupakan bagian dari logika proses pada file ini.
            $payload['status'] = $validated['status'];
        // Menutup blok kode.
        }

        // Memperbarui data yang sudah ada di database.
        $user->update($payload);

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('users.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'User berhasil diperbarui.');
    // Menutup blok kode.
    }

    // Mendefinisikan method toggleStatus untuk menjalankan proses tertentu.
    public function toggleStatus(Pengguna $user)
    // Membuka blok kode.
    {
        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if (! Schema::hasColumn('users', 'status')) {
            // Mengalihkan pengguna ke halaman lain setelah proses selesai.
            return redirect()->route('users.index')
                // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
                ->with('error', 'Kolom status user tidak tersedia di database ini.');
        // Menutup blok kode.
        }

        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if ((int) $user->id === (int) auth()->id()) {
            // Mengalihkan pengguna ke halaman lain setelah proses selesai.
            return redirect()->route('users.index')
                // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
                ->with('error', 'Status akun yang sedang dipakai tidak bisa dinonaktifkan.');
        // Menutup blok kode.
        }

        // Menyimpan hasil proses ke dalam variabel untuk dipakai pada langkah berikutnya.
        $newStatus = ($user->status ?? 'aktif') === 'aktif' ? 'nonaktif' : 'aktif';
        // Memperbarui data yang sudah ada di database.
        $user->update(['status' => $newStatus]);

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('users.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'Status user berhasil diperbarui.');
    // Menutup blok kode.
    }

    // Mendefinisikan method destroy untuk menjalankan proses tertentu.
    public function destroy(Pengguna $user)
    // Membuka blok kode.
    {
        // Memeriksa kondisi untuk menentukan alur proses berikutnya.
        if ((int) $user->id === (int) auth()->id()) {
            // Mengalihkan pengguna ke halaman lain setelah proses selesai.
            return redirect()->route('users.index')
                // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
                ->with('error', 'Akun yang sedang digunakan tidak bisa dihapus.');
        // Menutup blok kode.
        }

        // Menghapus data dari database.
        $user->delete();

        // Mengalihkan pengguna ke halaman lain setelah proses selesai.
        return redirect()->route('users.index')
            // Memuat relasi data agar informasi yang dibutuhkan ikut tersedia.
            ->with('success', 'User berhasil dihapus.');
    // Menutup blok kode.
    }
// Menutup blok kode.
}
