<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear Permisos
        $permissions = [
            // Productos
            ['name' => 'Ver Productos', 'slug' => 'view-products', 'description' => 'Puede ver la lista de productos'],
            ['name' => 'Crear Productos', 'slug' => 'create-products', 'description' => 'Puede crear nuevos productos'],
            ['name' => 'Editar Productos', 'slug' => 'edit-products', 'description' => 'Puede editar productos existentes'],
            ['name' => 'Eliminar Productos', 'slug' => 'delete-products', 'description' => 'Puede eliminar productos'],
            
            // Categorías
            ['name' => 'Ver Categorías', 'slug' => 'view-categories', 'description' => 'Puede ver la lista de categorías'],
            ['name' => 'Crear Categorías', 'slug' => 'create-categories', 'description' => 'Puede crear nuevas categorías'],
            ['name' => 'Editar Categorías', 'slug' => 'edit-categories', 'description' => 'Puede editar categorías existentes'],
            ['name' => 'Eliminar Categorías', 'slug' => 'delete-categories', 'description' => 'Puede eliminar categorías'],
            
            // Usuarios
            ['name' => 'Ver Usuarios', 'slug' => 'view-users', 'description' => 'Puede ver la lista de usuarios'],
            ['name' => 'Crear Usuarios', 'slug' => 'create-users', 'description' => 'Puede crear nuevos usuarios'],
            ['name' => 'Editar Usuarios', 'slug' => 'edit-users', 'description' => 'Puede editar usuarios existentes'],
            ['name' => 'Eliminar Usuarios', 'slug' => 'delete-users', 'description' => 'Puede eliminar usuarios'],
            
            // Roles
            ['name' => 'Ver Roles', 'slug' => 'view-roles', 'description' => 'Puede ver la lista de roles'],
            ['name' => 'Crear Roles', 'slug' => 'create-roles', 'description' => 'Puede crear nuevos roles'],
            ['name' => 'Editar Roles', 'slug' => 'edit-roles', 'description' => 'Puede editar roles existentes'],
            ['name' => 'Eliminar Roles', 'slug' => 'delete-roles', 'description' => 'Puede eliminar roles'],
            
            // Pedidos
            ['name' => 'Ver Pedidos', 'slug' => 'view-orders', 'description' => 'Puede ver la lista de pedidos'],
            ['name' => 'Editar Pedidos', 'slug' => 'edit-orders', 'description' => 'Puede editar pedidos'],
            
            // Ofertas
            ['name' => 'Ver Ofertas', 'slug' => 'view-offers', 'description' => 'Puede ver la lista de ofertas'],
            ['name' => 'Crear Ofertas', 'slug' => 'create-offers', 'description' => 'Puede crear nuevas ofertas'],
            ['name' => 'Editar Ofertas', 'slug' => 'edit-offers', 'description' => 'Puede editar ofertas existentes'],
            ['name' => 'Eliminar Ofertas', 'slug' => 'delete-offers', 'description' => 'Puede eliminar ofertas'],
            
            // Banners
            ['name' => 'Ver Banners', 'slug' => 'view-banners', 'description' => 'Puede ver la lista de banners'],
            ['name' => 'Crear Banners', 'slug' => 'create-banners', 'description' => 'Puede crear nuevos banners'],
            ['name' => 'Editar Banners', 'slug' => 'edit-banners', 'description' => 'Puede editar banners existentes'],
            ['name' => 'Eliminar Banners', 'slug' => 'delete-banners', 'description' => 'Puede eliminar banners'],
            
            // Configuración
            ['name' => 'Ver Configuración', 'slug' => 'view-settings', 'description' => 'Puede ver la configuración del sitio'],
            ['name' => 'Editar Configuración', 'slug' => 'edit-settings', 'description' => 'Puede editar la configuración del sitio'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                ['slug' => $permissionData['slug']],
                ['name' => $permissionData['name'], 'description' => $permissionData['description']]
            );
        }

        // Crear Roles
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrador',
                'description' => 'Acceso completo al sistema'
            ]
        );

        $editorRole = Role::firstOrCreate(
            ['slug' => 'editor'],
            [
                'name' => 'Editor',
                'description' => 'Puede gestionar productos y categorías'
            ]
        );

        $viewerRole = Role::firstOrCreate(
            ['slug' => 'viewer'],
            [
                'name' => 'Visualizador',
                'description' => 'Solo puede ver información'
            ]
        );

        // Asignar todos los permisos al admin
        // sync() reemplaza todos los permisos, asegurando que siempre estén actualizados
        $adminRole->permissions()->sync(Permission::all());

        // Asignar permisos específicos al editor
        $editorPermissions = Permission::whereIn('slug', [
            'view-products', 'create-products', 'edit-products', 'delete-products',
            'view-categories', 'create-categories', 'edit-categories', 'delete-categories',
            'view-orders', 'edit-orders',
            'view-offers', 'create-offers', 'edit-offers', 'delete-offers',
            'view-banners', 'create-banners', 'edit-banners', 'delete-banners'
        ])->pluck('id');
        $editorRole->permissions()->sync($editorPermissions);

        // Asignar permisos de solo lectura al viewer
        $viewerPermissions = Permission::whereIn('slug', [
            'view-products', 'view-categories', 'view-orders', 'view-offers', 'view-banners'
        ])->pluck('id');
        $viewerRole->permissions()->sync($viewerPermissions);

        // Crear usuario administrador (solo si no existe)
        $admin = User::firstOrCreate(
            ['email' => 'admin@catering.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
            ]
        );
        if (!$admin->roles->contains($adminRole->id)) {
            $admin->roles()->attach($adminRole);
        }

        // Crear usuario editor (solo si no existe)
        $editor = User::firstOrCreate(
            ['email' => 'editor@catering.com'],
            [
                'name' => 'Editor',
                'password' => Hash::make('editor123'),
            ]
        );
        if (!$editor->roles->contains($editorRole->id)) {
            $editor->roles()->attach($editorRole);
        }

        // Crear usuario viewer (solo si no existe)
        $viewer = User::firstOrCreate(
            ['email' => 'viewer@catering.com'],
            [
                'name' => 'Visualizador',
                'password' => Hash::make('viewer123'),
            ]
        );
        if (!$viewer->roles->contains($viewerRole->id)) {
            $viewer->roles()->attach($viewerRole);
        }
    }
}
