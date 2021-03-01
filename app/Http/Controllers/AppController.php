<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppController extends Controller
{
    public function index(Request $request)
    {
        try {
            $db = DB::table('migrations')->get();
            return response()->json(array('status' => 'ok', 'code' => 200, 'migrations' => $db), 200);
        } catch (\Exception $error) {
            return response()->json(array('status' => 'error', 'code' => 500, 'message' => $error->getMessage()), 500);
        }
    }
}
