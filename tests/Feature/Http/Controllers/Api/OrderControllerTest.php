<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Adapters\StripeAdapter;
use App\Mail\OrderInvoice;
use App\Models\Carpark;
use App\Models\Order;
use App\Models\User;
use App\Models\Vehicle;
use App\Schemas\OrderStatusSchema;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Tests\Traits\StripeMockable;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;
    use StripeMockable;

    /** @var User */
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->user = User::factory()->create());
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_can_create_an_order(): void
    {
        $carpark = Carpark::factory()->create();
        $vehicle = Vehicle::factory()->create(['carpark_id' => $carpark->id]);

        $this->post(route('api.order.create'), [
            'user_id' => $this->user->id, // @todo change to use middleware/bearer
            'vehicle_id' => $vehicle->id,
            'from_date' => Carbon::now(),
            'to_date' => Carbon::now()->addDay(),
            'uber_pickup' => false,
            'total' => 100.00,
            'user_location' => [
                'lat' => 10.0,
                'lng' => 20.0,
            ],
        ]);

        $this->assertNotNull(Order::first());
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_can_create_an_order_with_an_uber(): void
    {
        $carpark = Carpark::factory()->create();
        $vehicle = Vehicle::factory()->create(['carpark_id' => $carpark->id]);

        $this->post(route('api.order.create'), [
            'user_id' => $this->user->id, // @todo change to use middleware/bearer
            'vehicle_id' => $vehicle->id,
            'from_date' => Carbon::now(),
            'to_date' => Carbon::now()->addDay(),
            'uber_pickup' => true,
            'uber_route' => ['sample_data'],
            'total' => 100.00,
            'user_location' => [
                'lat' => 10.0,
                'lng' => 20.0,
            ],
        ]);

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertNotNull($order->uber);
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_cannot_create_an_order_without_valid_fields(): void
    {
        $response = $this->post(route('api.order.create'), [
            'vehicle_id' => 999,
            'from_date' => 'bad_date',
            'to_date' => 1233123,
            'uber_pickup' => 'false',
        ]);

        $response->assertJsonStructure(['errors' => [
            'vehicle_id',
            'from_date',
            'to_date',
        ]]);
        $response->assertUnprocessable();
    }

    /**
     * @test
     *
     * @return
     */
    public function it_can_charge_a_stripe_token_and_mark_order_as_paid(): void
    {
        $this->requireStripeToBeConfigured();

        Mail::fake();
        $carpark = Carpark::factory()->create();
        $vehicle = Vehicle::factory()->create(['carpark_id' => $carpark->id]);
        $order = $this->mockOrder($vehicle);

        $response = $this->post(route('api.order.payment', $order->id), [
            'stripe' => $this->mockStripeCardToken(),
        ]);

        $this->assertTrue($response->json('success'));
        $this->assertEquals(OrderStatusSchema::PAID, $order->refresh()->status);
        Mail::assertSent(OrderInvoice::class);
    }

    /**
     * @test
     * @dataProvider invalidDateTimeRangeDataProvider
     *
     * @param Carbon $fromDate
     * @param Carbon $toDate
     * @param array $expectedErrors
     * @return void
     */
    public function it_will_not_create_an_order_without_a_valid_date_range(
        Carbon $fromDate,
        Carbon $toDate,
        array $expectedErrors
    ): void
    {
        $carpark = Carpark::factory()->create();
        $vehicle = Vehicle::factory()->create(['carpark_id' => $carpark->id]);

        $response = $this->post(route('api.order.create'), [
            'user_id' => $this->user->id, // @todo change to use middleware/bearer
            'vehicle_id' => $vehicle->id,
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'uber_pickup' => false,
            'total' => 100.00,
            'user_location' => [
                'lat' => 10.0,
                'lng' => 20.0,
            ],
        ]);
        if (filled($expectedErrors)) {
            $response->assertJsonStructure(['errors' => $expectedErrors]);
        } else {
            $response->assertCreated();
        }
    }

    public function invalidDateTimeRangeDataProvider(): array
    {
        return [
            'fromDate is below current date' => [
                'from_date' => Carbon::now()->subDay(),
                'to_date' => Carbon::now()->addDays(2),
                'expected_errors' => ['from_date'],
            ],
            'fromDate is at minimum the current date' => [
                'from_date' => Carbon::now()->addDay(),
                'to_date' => Carbon::now()->addDays(2),
                'expected_errors' => [],
            ],
            'fromDate and toDate have at minimum a 1 day spread' => [
                'from_date' => Carbon::now()->addDay(),
                'to_date' => Carbon::now()->addDay(),
                'expected_errors' => ['to_date'],
            ]
        ];
    }

    /**
     * @param Vehicle $vehicle
     * @return Order
     */
    private function mockOrder(Vehicle $vehicle): Order
    {
        return Order::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'from_date' => Carbon::now(),
            'to_date' => Carbon::now()->addDay(),
            'uber_pickup' => false,
            'total' => 100.00,
            'status' => OrderStatusSchema::UNPAID,
        ]);
    }
}
