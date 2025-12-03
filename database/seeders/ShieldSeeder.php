<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Filament\Facades\Filament;
use Illuminate\Support\Str;

class ShieldSeeder extends Seeder
 {
    public function run(): void
 {
        // ------------------------------------
        // 1. 分野（resources）から Permission を自動生成
        // ------------------------------------
        $permissions = [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];

        // Filament リソースごとに permissions を作成
        $filamentResources = config( 'filament.resources' ) ?? [];

        foreach ( $filamentResources as $resourceClass ) {
            $resourceName = Str::kebab( class_basename( $resourceClass ) );

            foreach ( $permissions as $p ) {
                Permission::firstOrCreate( [
                    'name' => "{$p}_{$resourceName}"
                ] );
            }
        }

        // ------------------------------------
        // 2. ロール作成（5つ）
        // ------------------------------------
        $roles = [
            'admin',
            'manager',
            'editor',
            'staff',
            'viewer',
        ];

        foreach ( $roles as $roleName ) {
            Role::firstOrCreate( [ 'name' => $roleName ] );
        }

        // ------------------------------------
        // 3. 全権限を admin に付与
        // ------------------------------------
        $admin = Role::where( 'name', 'admin' )->first();
        $admin->givePermissionTo( Permission::all() );

        // ------------------------------------
        // 4. 他ロールは必要に応じて制限
        // ------------------------------------
        Role::where( 'name', 'manager' )
        ->first()
        ?->givePermissionTo(
            Permission::where( 'name', 'like', '%view%' )->get()
        );

        Role::where( 'name', 'editor' )
        ->first()
        ?->givePermissionTo(
            Permission::where( 'name', 'like', '%update%' )->get()
        );

        Role::where( 'name', 'staff' )
        ->first()
        ?->givePermissionTo(
            Permission::where( 'name', 'like', 'view_%' )->get()
        );

        // viewer は view のみ
        Role::where( 'name', 'viewer' )
        ->first()
        ?->givePermissionTo(
            Permission::where( 'name', 'like', 'view_%' )->get()
        );
    }
}