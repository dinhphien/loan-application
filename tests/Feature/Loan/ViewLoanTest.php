<?php

namespace Tests\Feature\Loan;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewLoanTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_view_his_own_loan(): void
    {
        $user = User::factory()->create();
        $this->createLoanWithUser($user);

        $insertedLoan = Loan::latest()->first();

        $response = $this->actingAs($user)->getJson('/api/loans/' . $insertedLoan->id);

        $response->assertStatus(200);
        $response->assertJson(
            [
                'loan' => Loan::with('repayments')
                    ->find($insertedLoan->id)
                    ->toArray()
            ]
        );
    }

    public function test_user_cannot_view_other_loan(): void
    {
        $user = User::factory()->create();
        $this->createLoanWithUser($user);
        $insertedLoan = Loan::latest()->first();

        $anotherUser = User::factory()->create();

        $response = $this->actingAs($anotherUser)->getJson('/api/loans/' . $insertedLoan->id);

        $response->assertStatus(403);
        $response->assertSeeText('Permission denied!');

    }

    public function test_user_cannot_view_non_existing_loan(): void
    {
        $user = User::factory()->create();
        $this->createLoanWithUser($user);

        $response = $this->actingAs($user)->getJson('/api/loans/' . rand(50, 100));

        $response->assertStatus(400);
        $response->assertSeeText('Loan not found!');
    }

    private function createLoanWithUser(User $user): void
    {
        $this->actingAs($user)->postJson('/api/loans', [
            'amount' => 1000000,
            'term' => rand(1, 10)
        ]);
    }
}
