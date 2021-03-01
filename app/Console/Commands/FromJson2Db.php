<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Carbon;

class FromJson2Db extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'json2:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo "Start putting the data to the DB" . PHP_EOL;
        $users = json_decode(file_get_contents('data.json'), true);

        foreach ($users['users'] as $user) {
            $newUser = new User(array(
                'id' => $user['id'],
                'name' => $user['name'],
                'age' => (int)$user['age']
            ));

            $newUser->save();

            foreach ($user['companies'] as $company) {
                $comp = Company::find($company['id']);
                if (!$comp) {
                    $newCompany = new Company(array(
                        'id' => $company['id'],
                        'name' => $company['name'],
                        'started_at' => Carbon::parse($company['started_at'])
                    ));
                    $newCompany->save();
                }
                User::find($user['id'])->companies()->attach($company['id']);
            }
        }

        echo "End putting the data to the DB" . PHP_EOL;
        return 0;
    }
}
