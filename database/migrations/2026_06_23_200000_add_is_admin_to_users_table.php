<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('email');
        });

        // Seed a default admin user
        if (!User::where('email', 'admin@vestnook.com')->exists()) {
            User::create([
                'name' => 'Admin Vestnook',
                'email' => 'admin@vestnook.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]);
        }

        // Upgrade the first registered user to admin for local testing convenience
        $firstUser = User::find(1);
        if ($firstUser) {
            $firstUser->is_admin = true;
            $firstUser->save();
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
