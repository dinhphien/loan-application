<?php

namespace Tests\Feature\Loan;

use App\Models\Loan;
use App\Models\Repayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApproveLoanTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_approve_loan(): void
    {
        $this->createLoan();
        $insertedLoan = Loan::latest()->first();

        $response = $this->actingAs($this->admin)->post('/api/loans/' . $insertedLoan->id . '/approve');

        $response->assertStatus(200);
        $response->assertSeeText('Approved loan successfully!');

        $this->assertDatabaseHas(
            'loans',
            [
                'id' => $insertedLoan->id,
                'status' => Loan::APPROVED
            ]
        );
        $this->assertDatabaseHas(
            'repayments',
            [
                'loan_id' => $insertedLoan->id,
                'status' => Repayment::APPROVED
            ]
        );
    }

    public function test_admin_can_not_approve_non_existing_loan(): void
    {
        $this->createLoan();

        $response = $this->actingAs($this->admin)->post('/api/loans/' . rand(50, 100) . '/approve');

        $response->assertStatus(400);
        $response->assertSeeText('Loan not found!');
    }

    public function test_admin_can_not_approve_already_approved_loan(): void
    {
        $this->createLoan();
        $insertedLoan = Loan::latest()->first();
        $this->actingAs($this->admin)->post('/api/loans/' . $insertedLoan->id . '/approve');

        $response = $this->actingAs($this->admin)->post('/api/loans/' . $insertedLoan->id . '/approve');
        $response->assertStatus(400);
        $response->assertSeeText('Loan already approved!');
    }

    public function test_non_admin_user_can_not_approve_loan(): void
    {
        $this->createLoan();
        $insertedLoan = Loan::latest()->first();

        $response = $this->actingAs($this->user)->post('/api/loans/' . $insertedLoan->id . '/approve');

        $response->assertStatus(403);
        $response->assertSeeText('Missing permission!');
    }

    private function createLoan(): void
    {
        $this->actingAs($this->user)->postJson('/api/loans', [
            'amount' => 1000000,
            'term' => rand(1, 10)
        ]);
    }
}
