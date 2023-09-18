<?php

namespace Tests\Feature\Loan\Repayment;

use App\Models\Loan;
use App\Models\Repayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddRepaymentTest extends TestCase
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

    public function test_user_can_add_repayment_successfully(): void
    {
        $loan = $this->createLoanWithUser($this->user, 100000, rand(2, 10));

        $repayment = $loan->repayments()->first();

        $this->approveLoan($loan->id);

        $response = $this->actingAs($this->user)->postJson('/api/repayments/' . $repayment->id, [
            'amount' => $repayment->amount
        ]);

        $response->assertStatus(200);
        $response->assertSeeText('Pay repayment successfully!');
        $this->assertDatabaseHas('repayments', ['id' => $repayment->id, 'status' => Repayment::PAID]);
        $this->assertDatabaseHas('loans', ['id' => $loan->id, 'status' => Loan::APPROVED]);
    }

    public function test_user_approve_the_latest_repayment_and_loan_becomes_paid(): void
    {
        $loan = $this->createLoanWithUser($this->user, 100000, rand(1, 10));

        $this->approveLoan($loan->id);

        foreach($loan->repayments()->get() as $repayment) {
            $this->actingAs($this->user)->postJson('/api/repayments/' . $repayment->id, [
                'amount' => $repayment->amount
            ]);
        }

        $this->assertDatabaseHas('loans', ['id'=> $loan->id, 'status' => Loan::PAID]);
    }

    public function test_user_cannot_pay_a_repayment_twice(): void
    {
        $loan = $this->createLoanWithUser($this->user, 100000, rand(2, 10));

        $repayment = $loan->repayments()->first();

        $this->approveLoan($loan->id);

        $this->actingAs($this->user)->postJson('/api/repayments/' . $repayment->id, [
            'amount' => $repayment->amount
        ]);

        $response = $this->actingAs($this->user)->postJson('/api/repayments/' . $repayment->id, [
            'amount' => $repayment->amount
        ]);

        $response->assertStatus(400);
        $response->assertSeeText('Repayment already paid!');
    }

    public function test_user_cannot_pay_repayments_of_non_approved_loan(): void
    {
        $loan = $this->createLoanWithUser($this->user, 100000, rand(2, 10));

        $repayment = $loan->repayments()->first();

        $response = $this->actingAs($this->user)->postJson('/api/repayments/' . $repayment->id, [
            'amount' => $repayment->amount
        ]);

        $response->assertStatus(400);
        $response->assertSeeText('repayment is not approved yet!');
    }

    public function test_user_cannot_pay_smaller_than_repayment_amount(): void
    {
        $loan = $this->createLoanWithUser($this->user, 100000, rand(2, 10));

        $repayment = $loan->repayments()->first();

        $this->approveLoan($loan->id);

        $response = $this->actingAs($this->user)->postJson('/api/repayments/' . $repayment->id, [
            'amount' => 1000
        ]);

        $response->assertStatus(400);
        $response->assertSeeText('money is not enough for this repayment!');
    }

    public function test_user_cannot_pay_another_people_repayment(): void
    {
        $loan = $this->createLoanWithUser($this->user, 100000, rand(2, 10));

        $repayment = $loan->repayments()->first();

        $this->approveLoan($loan->id);

        $anotherUser = User::factory()->create();
        $response = $this->actingAs($anotherUser)->postJson('/api/repayments/' . $repayment->id, [
            'amount' => $repayment->amount
        ]);

        $response->assertStatus(403);
        $response->assertSeeText('Permission denied!');

    }

    public function test_user_cannot_pay_with_non_existing_repayment(): void
    {
        $loan = $this->createLoanWithUser($this->user, 100000, rand(2, 5));

        $repayment = $loan->repayments()->first();

        $this->approveLoan($loan->id);

        $response = $this->actingAs($this->user)->postJson('/api/repayments/6', [
            'amount' => $repayment->amount
        ]);

        $response->assertStatus(400);
        $response->assertSeeText('Repayment not found!');
    }

    public function test_user_cannot_pay_with_the_wrong_amount_format(): void
    {
        $loan = $this->createLoanWithUser($this->user, 100000, rand(2, 5));

        $loan->repayments()->first();

        $this->approveLoan($loan->id);

        $response = $this->actingAs($this->user)->postJson('/api/repayments/6', [
            'amount' => 'a-random-value'
        ]);

        $response->assertStatus(422);
        $response->assertInvalid(['amount']);
    }

    private function createLoanWithUser(User $user, float $amount, int $term): Loan
    {
        $this->actingAs($user)->postJson('/api/loans', [
            'amount' => $amount,
            'term' => $term
        ]);

        return Loan::latest()->with('repayments')->first();
    }

    private function approveLoan(int $loanId): void
    {
        $this->actingAs($this->admin)->postJson('/api/loans/' . $loanId . '/approve');
    }
}
