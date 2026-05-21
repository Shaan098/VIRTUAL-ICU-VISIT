<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\User;
use App\Models\VisitRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ─────────────────────────────────────────────────────────────
        $admin = User::create([
            'name'      => 'Admin User',
            'email'     => 'admin@icuvisit.com',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'phone'     => '+1-555-0100',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // ── Doctors ───────────────────────────────────────────────────────────
        $doctors = [
            ['name' => 'Dr. Sarah Mitchell', 'email' => 'doctor1@icuvisit.com', 'specialty' => 'Intensivist'],
            ['name' => 'Dr. James Patel',    'email' => 'doctor2@icuvisit.com', 'specialty' => 'Pulmonologist'],
        ];

        $doctorModels = [];
        foreach ($doctors as $d) {
            $doctorModels[] = User::create([
                'name'      => $d['name'],
                'email'     => $d['email'],
                'password'  => Hash::make('password'),
                'role'      => 'doctor',
                'phone'     => '+1-555-0' . rand(100, 199),
                'specialty' => $d['specialty'],
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

        // ── Family Members ────────────────────────────────────────────────────
        $families = [
            ['name' => 'Emily Johnson',  'email' => 'family1@icuvisit.com'],
            ['name' => 'Robert Chen',    'email' => 'family2@icuvisit.com'],
            ['name' => 'Maria Garcia',   'email' => 'family3@icuvisit.com'],
            ['name' => 'David Williams', 'email' => 'family4@icuvisit.com'],
            ['name' => 'Lisa Anderson',  'email' => 'family5@icuvisit.com'],
        ];

        $familyModels = [];
        foreach ($families as $f) {
            $familyModels[] = User::create([
                'name'      => $f['name'],
                'email'     => $f['email'],
                'password'  => Hash::make('password'),
                'role'      => 'family',
                'phone'     => '+1-555-0' . rand(200, 299),
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

        // ── Patients ──────────────────────────────────────────────────────────
        $patientsData = [
            ['name' => 'Michael Johnson',  'diagnosis' => 'Severe Pneumonia',            'status' => 'critical', 'ward' => 'ICU Ward A', 'bed' => 'A-01', 'age' => 67, 'blood' => 'O+'],
            ['name' => 'Eleanor Chen',     'diagnosis' => 'Acute Respiratory Failure',    'status' => 'active',   'ward' => 'ICU Ward A', 'bed' => 'A-02', 'age' => 72, 'blood' => 'A+'],
            ['name' => 'Carlos Garcia',    'diagnosis' => 'Post-Cardiac Surgery',         'status' => 'stable',   'ward' => 'ICU Ward B', 'bed' => 'B-01', 'age' => 58, 'blood' => 'B-'],
            ['name' => 'Patricia Williams','diagnosis' => 'Septic Shock',                 'status' => 'critical', 'ward' => 'ICU Ward B', 'bed' => 'B-02', 'age' => 64, 'blood' => 'AB+'],
            ['name' => 'Thomas Anderson',  'diagnosis' => 'Traumatic Brain Injury',       'status' => 'active',   'ward' => 'ICU Ward C', 'bed' => 'C-01', 'age' => 45, 'blood' => 'O-'],
            ['name' => 'Susan Martinez',   'diagnosis' => 'Multi-Organ Failure',          'status' => 'critical', 'ward' => 'ICU Ward A', 'bed' => 'A-03', 'age' => 79, 'blood' => 'A-'],
            ['name' => 'William Taylor',   'diagnosis' => 'ARDS',                         'status' => 'stable',   'ward' => 'ICU Ward C', 'bed' => 'C-02', 'age' => 53, 'blood' => 'B+'],
            ['name' => 'Jennifer Brown',   'diagnosis' => 'Diabetic Ketoacidosis',        'status' => 'active',   'ward' => 'ICU Ward B', 'bed' => 'B-03', 'age' => 38, 'blood' => 'O+'],
            ['name' => 'Richard Davis',    'diagnosis' => 'Acute Kidney Injury',          'status' => 'stable',   'ward' => 'ICU Ward A', 'bed' => 'A-04', 'age' => 61, 'blood' => 'A+'],
            ['name' => 'Barbara Wilson',   'diagnosis' => 'Stroke - Hemorrhagic',         'status' => 'active',   'ward' => 'ICU Ward C', 'bed' => 'C-03', 'age' => 70, 'blood' => 'AB-'],
        ];

        $patientModels = [];
        foreach ($patientsData as $i => $p) {
            $doctor = $doctorModels[$i % 2];
            $patientModels[] = Patient::create([
                'name'               => $p['name'],
                'diagnosis'          => $p['diagnosis'],
                'status'             => $p['status'],
                'ward'               => $p['ward'],
                'bed_number'         => $p['bed'],
                'age'                => $p['age'],
                'blood_group'        => $p['blood'],
                'gender'             => $i % 2 === 0 ? 'male' : 'female',
                'assigned_doctor_id' => $doctor->id,
                'admission_date'     => now()->subDays(rand(1, 30)),
                'notes'              => 'Patient admitted to ICU. Monitoring vitals continuously.',
                'emergency_contact_name'  => 'Next of Kin',
                'emergency_contact_phone' => '+1-555-0' . rand(300, 399),
            ]);
        }

        // ── Visit Requests ────────────────────────────────────────────────────
        $statuses = ['pending', 'approved', 'rejected', 'completed'];
        foreach ($patientModels as $i => $patient) {
            $family = $familyModels[$i % 5];
            $status = $statuses[$i % 4];

            $vr = VisitRequest::create([
                'patient_id'         => $patient->id,
                'requested_by'       => $family->id,
                'assigned_doctor_id' => $patient->assigned_doctor_id,
                'requested_date'     => now()->addDays(rand(1, 7))->toDateString(),
                'requested_time'     => ['10:00', '14:00', '16:00'][$i % 3],
                'reason'             => 'Family visit to check on patient\'s condition and speak with the attending physician.',
                'status'             => $status,
                'rejection_reason'   => $status === 'rejected' ? 'Patient is in a critical condition and cannot receive visitors at this time.' : null,
            ]);

            // Create meetings for approved/completed
            if (in_array($status, ['approved', 'completed'])) {
                $roomName = 'ICUVisit-' . strtoupper(substr(md5($vr->id . 'seed'), 0, 10));
                Meeting::create([
                    'visit_request_id' => $vr->id,
                    'room_name'        => $roomName,
                    'room_password'    => strtoupper(substr(md5($roomName), 0, 12)),
                    'jitsi_url'        => "https://meet.jit.si/{$roomName}",
                    'scheduled_at'     => now()->addHours(rand(2, 48)),
                    'status'           => $status === 'completed' ? 'completed' : 'scheduled',
                    'host_id'          => $patient->assigned_doctor_id,
                    'duration_minutes' => 30,
                ]);
            }

            // Create notifications
            Notification::create([
                'user_id'       => $family->id,
                'title'         => match($status) {
                    'approved'  => '✅ Visit Request Approved',
                    'rejected'  => '❌ Visit Request Rejected',
                    'completed' => '🏁 Visit Completed',
                    default     => 'ℹ️ Visit Request Received',
                },
                'message'       => "Your visit request for {$patient->name} has been {$status}.",
                'type'          => match($status) {
                    'approved'  => 'success',
                    'rejected'  => 'danger',
                    'completed' => 'info',
                    default     => 'info',
                },
                'related_model' => 'VisitRequest',
                'related_id'    => $vr->id,
                'action_url'    => '/visit-requests/' . $vr->id,
                'read_at'       => in_array($status, ['completed', 'rejected']) ? now() : null,
            ]);
        }

        $this->command->info('✅ Seeded: 1 admin, 2 doctors, 5 family members, 10 patients, 10 visit requests.');
    }
}
