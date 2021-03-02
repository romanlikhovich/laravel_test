<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index(Request $request, $start = 0, $end = 200)
    {
        try {
            $companies = Company::all();
            $result = array();
            foreach ($companies as $company) {
                $res = array(
                    'id' => $company->id,
                    'name' => $company->name,
                    'started_at' => $company->started_at,
                    'age' => Carbon::parse($company->started_at)->age,
                    'users' => array(),
                );

                $users = $company->users()
                    ->where('age', '>=', $start)
                    ->where('age', '<=', $end)
                    ->get();

                foreach ($users as $user) {
                    array_push($res['users'], array(
                        'id' => $user->id,
                        'name' => $user->name,
                        'age' => $user->age
                    ));

                }
                if (count($users)) {
                    array_push($result, $res);
                }
            }
            return response()->json(array('status' => 'ok', 'code' => 200, 'companies' => $result), 200);
        } catch (\Exception $error) {
            return response()->json(array('status' => 'error', 'code' => 500, 'message' => $error->getMessage()), 500);
        }
    }
}
