<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Package;
use App\Models\Doctor;
use App\Models\Agent;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@cmcms.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'phone' => '+252634671911'
        ]);

        // Create Packages
        $maternalPackage = Package::create([
            'name' => 'Maternal Package',
            'description' => 'Comprehensive maternal care package',
            'price' => 2.00,
            'is_active' => true,
        ]);

        $chronicPackage = Package::create([
            'name' => 'Chronic Package - Diabetes',
            'description' => 'Diabetes management package',
            'price' => 3.00,
            'type' => 'Diabetes',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Chronic Package - Hypertension',
            'description' => 'Hypertension management package',
            'price' => 3.00,
            'type' => 'Hypertension',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Chronic Package - Both',
            'description' => 'Diabetes & Hypertension management',
            'price' => 3.00,
            'type' => 'Both',
            'is_active' => true,
        ]);

        $pediatricPackage = Package::create([
            'name' => 'Pediatric Package',
            'description' => 'Pediatric care package',
            'price' => 2.00,
            'is_active' => true,
        ]);

        // Create Doctor
        $doctorUser = User::create([
            'name' => 'Dr. John Smith',
            'email' => 'doctor@cmcms.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'status' => 'active',
            'phone' => '+1234567890',
        ]);

        Doctor::create([
            'user_id' => $doctorUser->id,
            'specialty' => 'General Medicine',
            'license_number' => 'DOC12345',
        ]);

        // Create Agent
        $agentUser = User::create([
            'name' => 'Agent One',
            'email' => 'agent@cmcms.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'status' => 'active',
            'phone' => '+1234567891',
        ]);

        Agent::create([
            'user_id' => $agentUser->id,
            'employee_id' => 'AGT001',
        ]);

        // Create Patient
        $patientUser = User::create([
            'name' => 'Patient One',
            'email' => 'patient@cmcms.com',
            'password' => Hash::make('password'),
            'role' => 'patient',
            'status' => 'active',
            'phone' => '+1234567892',
        ]);

        \App\Models\Patient::create([
            'user_id' => $patientUser->id,
            'phone' => '+1234567892',
            'city' => 'Mogadishu',
            'village' => 'Hamar Weyne',
            'age' => 35,
            'gender' => 'Male',
        ]);
    }
}