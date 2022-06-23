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
        $login_pin->token = 'eyJpdiI6Ik5mR1AydGxZSVB4QjVtb1VMNWlzT0E9PSIsInZhbHVlIjoiTzZnUnZTVnRYOGYySlpxUU5saTArL0dIbWszU2gyRzlPT3NxTGNORm9rcz0iLCJtYWMiOiI1ZGVmZmMwZGE0YzhmYmIzMTExMjI4Yzc0YWIwYzU2MjRiNmViMmFkMTNmNmExYjc3ZDVlMDhiOTkyN2Q5MjZkIiwidGFnIjoiIn0=';
        $login_pin->save();

        $login_pin = new LoginPin();
        $login_pin->user_id = 3;
        $login_pin->pin = 'JZ4C';
        $login_pin->token = 'eyJpdiI6Inhyelg2Rmdob1lSelI2Um9Walc1QlE9PSIsInZhbHVlIjoiWURtWStwL3UrbDI1aVJpY0N2aDg5U05aUjJYdG1pQ3JydGl6SnpobDA5ND0iLCJtYWMiOiJhN2U1ZTQxYWU4MzliMjBhOTAzNWIzMzZmZjc5MzE3MzQxYzVmNDBjODhiODYxOTE5ODc0NWUzMWYwNDU2ZDgyIiwidGFnIjoiIn0=';
        $login_pin->save();

    }


}
