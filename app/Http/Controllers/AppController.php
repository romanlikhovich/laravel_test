<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;

class AppController extends Controller
{
    public function index(Request $request, $start = null, $end = null)
    {
        try {
            $companies = Company::all();
            $result = array();
            foreach ($companies as $company) {
                $res = array(
                    'id' => $company->test_id,
                    'name' => $company->name,
                    'started_at' => $company->started_at,
                    'age' => Carbon::parse($company->started_at)->age,
                    'users' => array(),
                );
                $users = null;
                if (is_null($start) && is_null($end)) {
                    $users = $company->users()->get();
                } else if (!is_null($start) && is_null($end)) {
                    $users = $company->users()->where('age', '>=', $start)->get();
                } else if (is_null($start) && !is_null($end)) {
                    $users = $company->users()->where('age', '<=', $end)->get();
                } else {
                    $users = $company->users()->where('age', '>=', $start)->where('age', '<=', $end)->get();
                }
                foreach ($users as $user) {
                    $u = array(
                        'id' => $user->test_id,
                        'name' => $user->name,
                        'age' => $user->age
                    );
                    array_push($res['users'], $u);

                }
                array_push($result, $res);
            }
            return response()->json(array('status' => 'ok', 'code' => 200, 'companies' => $result), 200);
        } catch (\Exception $error) {
            return response()->json(array('status' => 'error', 'code' => 500, 'message' => $error->getMessage()), 500);
        }
    }
}
