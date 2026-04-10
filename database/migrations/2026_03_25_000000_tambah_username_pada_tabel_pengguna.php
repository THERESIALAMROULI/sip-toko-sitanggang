<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
return new class extends Migration
{
    public function up(): void
    {
        $columnWasAdded = false;
        if (! Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username', 50)->nullable()->after('email');
            });
            $columnWasAdded = true;
        }
        $reservedUsernames = DB::table('users')
            ->whereNotNull('username')
            ->where('username', '<>', '')
            ->pluck('username')
            ->map(fn ($username) => Str::lower((string) $username))
            ->all();
        DB::table('users')
            ->select('id', 'name', 'email', 'username')
            ->orderBy('id')
            ->get()
            ->each(function (object $user) use (&$reservedUsernames) {
                if (filled($user->username)) {
                    return;
                }
                $username = $this->generateUniqueUsername($user, $reservedUsernames);
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['username' => $username]);
                $reservedUsernames[] = Str::lower($username);
            });
        if ($columnWasAdded) {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('username');
            });
        }
    }
    public function down(): void
    {
    }
    private function generateUniqueUsername(object $user, array $reservedUsernames): string
    {
        $emailPrefix = Str::before((string) ($user->email ?? ''), '@');
        $baseUsername = blank($emailPrefix) ? (string) ($user->name ?? '') : $emailPrefix;
        $baseUsername = Str::of($baseUsername)
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9_]+/', '')
            ->trim('_')
            ->value();
        if ($baseUsername === '') {
            $baseUsername = 'user'.$user->id;
        }
        $baseUsername = Str::limit($baseUsername, 50, '');
        $candidate = $baseUsername;
        $suffix = 1;
        while (in_array(Str::lower($candidate), $reservedUsernames, true)) {
            $suffixText = (string) $suffix;
            $candidate = Str::limit($baseUsername, 50 - strlen($suffixText), '').$suffixText;
            $suffix++;
        }
        return $candidate;
    }
};
