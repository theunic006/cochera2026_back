<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard
            [
                'name' => 'Ver Dashboard',
                'slug' => 'dashboard.view',
                'description' => 'Permite ver el dashboard principal',
                'module' => 'Dashboard',
                'is_active' => true
            ],

            // Usuarios
            [
                'name' => 'Ver Usuarios',
                'slug' => 'users.view',
                'description' => 'Permite ver la lista de usuarios',
                'module' => 'Usuarios',
                'is_active' => true
            ],
            [
                'name' => 'Crear Usuarios',
                'slug' => 'users.create',
                'description' => 'Permite crear nuevos usuarios',
                'module' => 'Usuarios',
                'is_active' => true
            ],
            [
                'name' => 'Editar Usuarios',
                'slug' => 'users.edit',
                'description' => 'Permite editar usuarios existentes',
                'module' => 'Usuarios',
                'is_active' => true
            ],
            [
                'name' => 'Eliminar Usuarios',
                'slug' => 'users.delete',
                'description' => 'Permite eliminar usuarios',
                'module' => 'Usuarios',
                'is_active' => true
            ],

            // Roles
            [
                'name' => 'Ver Roles',
                'slug' => 'roles.view',
                'description' => 'Permite ver la lista de roles',
                'module' => 'Roles',
                'is_active' => true
            ],
            [
                'name' => 'Crear Roles',
                'slug' => 'roles.create',
                'description' => 'Permite crear nuevos roles',
                'module' => 'Roles',
                'is_active' => true
            ],
            [
                'name' => 'Editar Roles',
                'slug' => 'roles.edit',
                'description' => 'Permite editar roles existentes',
                'module' => 'Roles',
                'is_active' => true
            ],
            [
                'name' => 'Eliminar Roles',
                'slug' => 'roles.delete',
                'description' => 'Permite eliminar roles',
                'module' => 'Roles',
                'is_active' => true
            ],

            // Registros
            [
                'name' => 'Ver Registros',
                'slug' => 'registros.view',
                'description' => 'Permite ver la lista de registros',
                'module' => 'Registros',
                'is_active' => true
            ],
            [
                'name' => 'Crear Registros',
                'slug' => 'registros.create',
                'description' => 'Permite crear nuevos registros',
                'module' => 'Registros',
                'is_active' => true
            ],
            [
                'name' => 'Editar Registros',
                'slug' => 'registros.edit',
                'description' => 'Permite editar registros existentes',
                'module' => 'Registros',
                'is_active' => true
            ],
            [
                'name' => 'Eliminar Registros',
                'slug' => 'registros.delete',
                'description' => 'Permite eliminar registros',
                'module' => 'Registros',
                'is_active' => true
            ],

            // Ingresos
            [
                'name' => 'Ver Ingresos',
                'slug' => 'ingresos.view',
                'description' => 'Permite ver la lista de ingresos',
                'module' => 'Ingresos',
                'is_active' => true
            ],
            [
                'name' => 'Crear Ingresos',
                'slug' => 'ingresos.create',
                'description' => 'Permite crear nuevos ingresos',
                'module' => 'Ingresos',
                'is_active' => true
            ],
            [
                'name' => 'Editar Ingresos',
                'slug' => 'ingresos.edit',
                'description' => 'Permite editar ingresos existentes',
                'module' => 'Ingresos',
                'is_active' => true
            ],
            [
                'name' => 'Eliminar Ingresos',
                'slug' => 'ingresos.delete',
                'description' => 'Permite eliminar ingresos',
                'module' => 'Ingresos',
                'is_active' => true
            ],

            // Tolerancias
            [
                'name' => 'Ver Tolerancias',
                'slug' => 'tolerancias.view',
                'description' => 'Permite ver la configuración de tolerancias',
                'module' => 'Tolerancias',
                'is_active' => true
            ],
            [
                'name' => 'Editar Tolerancias',
                'slug' => 'tolerancias.edit',
                'description' => 'Permite editar la configuración de tolerancias',
                'module' => 'Tolerancias',
                'is_active' => true
            ],

            // Tipos de Vehículo
            [
                'name' => 'Ver Tipos de Vehículo',
                'slug' => 'tipos-vehiculo.view',
                'description' => 'Permite ver la lista de tipos de vehículo',
                'module' => 'Tipos de Vehículo',
                'is_active' => true
            ],
            [
                'name' => 'Crear Tipos de Vehículo',
                'slug' => 'tipos-vehiculo.create',
                'description' => 'Permite crear nuevos tipos de vehículo',
                'module' => 'Tipos de Vehículo',
                'is_active' => true
            ],
            [
                'name' => 'Editar Tipos de Vehículo',
                'slug' => 'tipos-vehiculo.edit',
                'description' => 'Permite editar tipos de vehículo existentes',
                'module' => 'Tipos de Vehículo',
                'is_active' => true
            ],
            [
                'name' => 'Eliminar Tipos de Vehículo',
                'slug' => 'tipos-vehiculo.delete',
                'description' => 'Permite eliminar tipos de vehículo',
                'module' => 'Tipos de Vehículo',
                'is_active' => true
            ],

            // Vehículos
            [
                'name' => 'Ver Vehículos',
                'slug' => 'vehiculos.view',
                'description' => 'Permite ver la lista de vehículos',
                'module' => 'Vehículos',
                'is_active' => true
            ],
            [
                'name' => 'Crear Vehículos',
                'slug' => 'vehiculos.create',
                'description' => 'Permite crear nuevos vehículos',
                'module' => 'Vehículos',
                'is_active' => true
            ],
            [
                'name' => 'Editar Vehículos',
                'slug' => 'vehiculos.edit',
                'description' => 'Permite editar vehículos existentes',
                'module' => 'Vehículos',
                'is_active' => true
            ],
            [
                'name' => 'Eliminar Vehículos',
                'slug' => 'vehiculos.delete',
                'description' => 'Permite eliminar vehículos',
                'module' => 'Vehículos',
                'is_active' => true
            ],

            // Reportes
            [
                'name' => 'Ver Reportes',
                'slug' => 'reportes.view',
                'description' => 'Permite ver y generar reportes',
                'module' => 'Reportes',
                'is_active' => true
            ],
            [
                'name' => 'Exportar Reportes',
                'slug' => 'reportes.export',
                'description' => 'Permite exportar reportes',
                'module' => 'Reportes',
                'is_active' => true
            ],

            // Salidas
            [
                'name' => 'Ver Salidas',
                'slug' => 'salidas.view',
                'description' => 'Permite ver la lista de salidas',
                'module' => 'Salidas',
                'is_active' => true
            ],
            [
                'name' => 'Registrar Salidas',
                'slug' => 'salidas.create',
                'description' => 'Permite registrar salidas de vehículos',
                'module' => 'Salidas',
                'is_active' => true
            ],

            // Observaciones
            [
                'name' => 'Ver Observaciones',
                'slug' => 'observaciones.view',
                'description' => 'Permite ver la lista de observaciones',
                'module' => 'Observaciones',
                'is_active' => true
            ],
            [
                'name' => 'Crear Observaciones',
                'slug' => 'observaciones.create',
                'description' => 'Permite crear nuevas observaciones',
                'module' => 'Observaciones',
                'is_active' => true
            ],
            [
                'name' => 'Editar Observaciones',
                'slug' => 'observaciones.edit',
                'description' => 'Permite editar observaciones existentes',
                'module' => 'Observaciones',
                'is_active' => true
            ],
            [
                'name' => 'Eliminar Observaciones',
                'slug' => 'observaciones.delete',
                'description' => 'Permite eliminar observaciones',
                'module' => 'Observaciones',
                'is_active' => true
            ],

            // Permisos (gestión de permisos)
            [
                'name' => 'Ver Permisos',
                'slug' => 'permissions.view',
                'description' => 'Permite ver la lista de permisos',
                'module' => 'Permisos',
                'is_active' => true
            ],
            [
                'name' => 'Asignar Permisos',
                'slug' => 'permissions.assign',
                'description' => 'Permite asignar permisos a usuarios',
                'module' => 'Permisos',
                'is_active' => true
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        $this->command->info('Permisos creados exitosamente');
    }
}
