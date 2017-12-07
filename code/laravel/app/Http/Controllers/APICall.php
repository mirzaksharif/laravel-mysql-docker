<?php
/*
|--------------------------------------------------------------------------
| Controller APICall
|--------------------------------------------------------------------------
|
| This controller fetch records from DB
| And formats it as per requried details.
|
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

class APICall extends Controller
{
    public function load(Request $request){
			
		$queryType = $request->input('type');
		
		$returnResult = array();
		
		//Accessing data from Model...
		$modCodes = \App\Code::selectRaw("CONCAT(YEAR(assigned_at), ':',  MONTH(assigned_at) , ':' ,  DAY(assigned_at) , ' ',  HOUR(assigned_at) , ':00') AS timerecord, 
										  COUNT(*) as total_count")
			->whereRaw("(assigned_at >= '2017-10-18 20:00:00' AND assigned_at < DATE_ADD('2017-10-18 20:01:52', INTERVAL 7 DAY)) AND type = '".$queryType."'")
			->groupBy( DB::raw('YEAR(assigned_at), MONTH(assigned_at), DAY(assigned_at), HOUR(assigned_at)') )
			->get();
		
		//Checking if QuerySting is provided or not...
		if (null !== $request->input('type')) {
			if (count($modCodes) > 0) {
				$returnResult['code'] = '200';
				$returnResult['count'] = count($modCodes);
				$returnResult['data'] = $modCodes;
			} else {
				$returnResult['code'] = '300';
				$returnResult['count'] = count($modCodes);
				$returnResult['data'] = 'No Results Found.';
			}
		} else {
				$returnResult['code'] = '404';
				$returnResult['error'] = "missing 'type'";
				$returnResult['data'] = 'No Results Found.';
		}
		
		//Sending proper JSON response with 'Content-Type:application/json' header
		return response()->json($returnResult);
		
	}
	
	
	//Implementing health check endpoint...
	public function healthCheck(Request $request){
		
		$healthCheck = array();
		
		try {
			DB::connection()->getPdo();
			if(DB::connection()->getDatabaseName()){
				$healthCheck['Database'] = "Database running successfully & connected to the DB: " . DB::connection()->getDatabaseName();	
			}
		} catch (\Exception $e) {
			$healthCheck['DB Error'] = "Could not connect to the database." .  $e->getMessage();
		}
		
		
		try {
			$apiRecords = \App\Code::count();
			$healthCheck['API Data'] = "There are total ". $apiRecords . " records found.";
	
		} catch (\Exception $e) {
			$healthCheck['DATA Error'] = "No API Records found. DB Table is empty. Please run SQL Dump file to import records.";
		}
		
		return response()->json($healthCheck);
		
	}
}
