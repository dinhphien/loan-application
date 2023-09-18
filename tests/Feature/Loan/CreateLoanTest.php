<?php

namespace Tests\Feature\Loan;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateLoanTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private string $uri;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->uri = '/api/loans';
    }

    public function test_create_a_loan_successfully(): void
    {
        $loanParams = [
            'amount' => 100000.00,
            'term' => 3
        ];

        $expectedLoanData = [
            'amount' => 100000.00,
            'term' => 3,
            'status' => Loan::PENDING,
            'user_id' => $this->user->id
        ];

        $response = $this->actingAs($this->user)->postJson($this->uri, $loanParams);

        $response->assertStatus(200);
        $response->assertSeeText('Loan created successfully');

        $this->assertDatabaseHas('loans', $expectedLoanData);
        $this->assertDatabaseCount('repayments', 3);
    }

    /**
     * @dataProvider provideInvalidLoanDataParams
     */
    public function test_create_loan_with_invalid_params(array $params, array $invalidFields): void
    {
        $response = $this->actingAs($this->user)->postJson($this->uri, $params);

        $response->assertStatus(422);
        $response->assertInvalid($invalidFields);
    }

    public static function provideInvalidLoanDataParams(): array
    {
        return [
            'amount and term are missing' => [[], ['amount', 'term']],
            'amount is missing' => [
                ['term' => 3],
                ['amount']
            ],
            'term is missing' => [
                ['amount' => 50000],
                ['term']
            ],
            'amount is invalid' => [
                ['amount' => 'not-a-price-value', 'term' => 3],
                ['amount']
            ],
            'term is not valid' => [
                ['amount' => 100000, 'term' => 'a-random-text'],
                ['term']
            ]
        ];
    }
}
