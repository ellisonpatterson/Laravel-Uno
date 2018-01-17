<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * An array of test users and roles to assign.
     *
     * @var array
     */
    public static $users = [
        [
            'username' => 'Rhododendron',
            'email' => 'emp5557@rit.edu',
            'password' => 'test123',
        ],
        [
            'username' => 'Frank',
            'email' => 'silence@xenogamers.org',
            'password' => 'test123',
        ],
        [
            'username' => 'Ted',
            'email' => 'tesafdsafdsa@xenogamers.org',
            'password' => 'test123',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$users as $key => $user) {
            self::$users[$key]['user_id'] = DB::table('user')->insertGetId([
                'username' => $user['username'],
                'email' => $user['email'],
                'password' => bcrypt($user['password']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
