<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user\'s first name, last name, and timezone';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            $firstname = fake()->firstName();
            $lastname = fake()->lastName();
            $user->update([
                'name' => $firstname . " " . $lastname,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'timezone' => fake()->randomElement(config('timezones')),
            ]);

        }
        $updatedCount = count($users);
        $this->info("$updatedCount users updated");

        return 0;
    }
}
