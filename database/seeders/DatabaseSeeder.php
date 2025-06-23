<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $roles = [
            ['name' => 'admin', 'display_name' => 'Administrador'],
            ['name' => 'operador', 'display_name' => 'Operador'],
            ['name' => 'cliente', 'display_name' => 'Cliente'],
        ];
        foreach ($roles as $roleData) {
            Role::firstOrCreate(['name' => $roleData['name']], $roleData);
        }
        // Asignar rol admin al primer usuario, operador al segundo, cliente al resto
        $users = User::all();
        $adminRole = Role::where('name', 'admin')->first();
        $operadorRole = Role::where('name', 'operador')->first();
        $clienteRole = Role::where('name', 'cliente')->first();
        if ($users->count() > 0) {
            $users[0]->roles()->syncWithoutDetaching([$adminRole->id]);
        }
        if ($users->count() > 1) {
            $users[1]->roles()->syncWithoutDetaching([$operadorRole->id]);
        }
        foreach ($users->slice(2) as $user) {
            $user->roles()->syncWithoutDetaching([$clienteRole->id]);
        }

        // Crear usuarios de prueba para cada rol
        $admin = User::firstOrCreate([
            'email' => 'admin@acuario.com'], [
            'name' => 'Administrador',
            'password' => Hash::make('admin1234'),
        ]);
        $operador = User::firstOrCreate([
            'email' => 'operador@acuario.com'], [
            'name' => 'Operador',
            'password' => Hash::make('operador1234'),
        ]);
        $cliente = User::firstOrCreate([
            'email' => 'cliente@acuario.com'], [
            'name' => 'Cliente',
            'password' => Hash::make('cliente1234'),
        ]);
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        $operador->roles()->syncWithoutDetaching([$operadorRole->id]);
        $cliente->roles()->syncWithoutDetaching([$clienteRole->id]);
    }
}
