<?php

namespace Database\Factories;

use App\Models\Party;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartyFactory extends Factory
{
    protected $model = Party::class;

    public function definition()
    {
        return [
            'party_name' => $this->faker->company(10),
            'owner_name' => $this->faker->name(10),
            'reference_name' => $this->faker->name(10),
            'email' => $this->faker->unique()->safeEmail,
            'mobile_no' => $this->faker->phoneNumber,
            'office_no' => $this->faker->phoneNumber,
            'address' => $this->faker->address(15),
            'state' => $this->faker->state,
            'state_code' => $this->faker->stateAbbr,
            'pan_no' => $this->faker->regexify('[A-Z]{5}[0-9]{4}[A-Z]{1}'),
            'gst_tin' => $this->faker->regexify('[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9]{1}')
        ];
    }
}
