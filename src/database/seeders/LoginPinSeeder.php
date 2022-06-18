<?php

namespace Database\Seeders;

use App\Models\LoginPin;
use Illuminate\Database\Seeder;

class LoginPinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $login_pin = new LoginPin();
        $login_pin->user_id = 3;
        $login_pin->pin = 'L54J';
        $login_pin->token = 'eyJpdiI6IjRYTjNNZm5qR3lndGp2dExFSHhNcEE9PSIsInZhbHVlIjoiaElDRE9CbVZzcEZmTzIreU5zNXg0dFFVRmpGQkR3dndYcFdJZWQ3RmhvUT0iLCJtYWMiOiI0ZjNkMzhmOWU2ODc1ZTNjM2MzMDE1MGJlNzZmYWMzYzVlNjc4OWVkOGFjZDZhMTEwOGVjODZkYzlmZjY5MzI0IiwidGFnIjoiIn0=';
        $login_pin->save();
    }


}
