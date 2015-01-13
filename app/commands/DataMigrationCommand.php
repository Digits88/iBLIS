<?php
 
use Illuminate\Console\Command;
 //The command <php artisan password:reset> resets all passwords to 'password'
class DataMigrationCommand extends Command {
 
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'password:reset';
 
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Migrate data";
 
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $users = User::all();
		foreach ($users as $user) {
        	$user->password = Hash::make('password');
			$user->save();
		}
    }
}