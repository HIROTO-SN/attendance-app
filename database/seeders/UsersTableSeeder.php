<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
 {
    public function run()
 {
        $faker = Faker::create();

        // 管理者ユーザーを作成
        User::create( [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make( 'password' ), // 必要なら強いパスワードに変更してください
            'is_admin' => true, // <-- is_admin カラムがある前提
            'remember_token' => Str::random( 10 ),
        ] );

        // 通常ユーザーを3人作成
        for ( $i = 1; $i <= 3; $i++ ) {
            User::create( [
                'name' => $faker->name,
                'email' => "user{$i}@example.com",
                'email_verified_at' => now(),
                'password' => Hash::make( 'password' ), // テスト用：'password'
                'is_admin' => false,
                'remember_token' => Str::random( 10 ),
            ] );
        }
    }
}