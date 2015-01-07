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
        $id = User::all();
		foreach ($id as $value) {
        	$password = Hash::make('password');
			$query = 'UPDATE users SET password=\''.$password.'\' WHERE id='.$value->id.';';
			DB::select(DB::raw($query));
		}
    }
}