<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $superAdmin = Role::firstOrCreate(['name' => 'superadministrador']);
        $admin      = Role::firstOrCreate(['name' => 'administrador']);
        $worker     = Role::firstOrCreate(['name' => 'trabajador']);

        // Create demo company
        $company = Company::firstOrCreate(
            ['slug' => 'demo-empresa'],
            [
                'name'    => 'Demo Empresa S.A.',
                'email'   => 'contacto@demo-empresa.com',
                'phone'   => '+34 900 000 000',
                'address' => 'Calle Principal 1, Madrid',
                'active'  => true,
            ]
        );

        // Create superadmin
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@multiempresa.test'],
            [
                'name'              => 'Super Administrador',
                'password'          => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdminUser->assignRole($superAdmin);

        // Create company admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@demo-empresa.test'],
            [
                'name'              => 'Admin Empresa',
                'password'          => bcrypt('password'),
                'company_id'        => $company->id,
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole($admin);

        // Create worker
        $workerUser = User::firstOrCreate(
            ['email' => 'trabajador@demo-empresa.test'],
            [
                'name'              => 'Trabajador Demo',
                'password'          => bcrypt('password'),
                'company_id'        => $company->id,
                'email_verified_at' => now(),
            ]
        );
        $workerUser->assignRole($worker);

        // Create demo tasks
        $tasks = [
            [
                'title'       => 'Revisar informe mensual',
                'description' => 'Revisar y aprobar el informe de ventas del mes de febrero.',
                'status'      => 'pendiente',
                'priority'    => 'alta',
                'due_date'    => now()->addDays(3),
            ],
            [
                'title'       => 'Actualizar documentación',
                'description' => 'Actualizar la documentación técnica del sistema.',
                'status'      => 'en_progreso',
                'priority'    => 'media',
                'due_date'    => now()->addDays(7),
            ],
            [
                'title'       => 'Reunión de equipo',
                'description' => 'Preparar presentación para la reunión semanal.',
                'status'      => 'completada',
                'priority'    => 'baja',
                'due_date'    => now()->subDays(1),
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::firstOrCreate(
                ['title' => $taskData['title'], 'company_id' => $company->id],
                array_merge($taskData, [
                    'company_id'  => $company->id,
                    'assigned_to' => $workerUser->id,
                    'created_by'  => $adminUser->id,
                ])
            );
        }
    }
}
