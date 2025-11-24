<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Group;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure there’s at least one user and one group first
        $user = User::first() ?? User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => 'member',
        ]);

        $group = Group::first() ?? Group::create([
            'name' => 'Test Group',
            'description' => 'Group for testing transactions',
            'created_by' => $user->id,
            'contribution_amount' => 1000,
        ]);

        // Create a few transactions
        Transaction::insert([
            [
                'group_id' => $group->id,
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => 5000,
                'reference' => 'TXN1001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group_id' => $group->id,
                'user_id' => $user->id,
                'type' => 'withdrawal',
                'amount' => 2000,
                'reference' => 'TXN1002',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

