<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\UserFile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'SuperAdmin',
            'email' => 'superAdmin@gmail.com',
            'password' => Hash::make('Pa55w0rd'),
            'role' => 'superAdmin',
        ]);

        DB::table('user_files')->insert([
            'name' => 'builtin',
            'path' => 'no_path',
            'user_id' => User::where('role', 'superAdmin')->first()->id,
        ]);

        DB::table('ontology_configs')->insert([
            'name' => 'builtin',
            'content' => Storage::get('ontology/builtin.json'),
            'user_file_id' => UserFile::where('name', 'builtin')->first()->id,
        ]);
    }
}
