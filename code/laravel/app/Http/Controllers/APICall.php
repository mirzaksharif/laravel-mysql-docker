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
		
		//Executing Raw Query ...
		$codes = DB::select("
			SELECT 
					CONCAT(YEAR(assigned_at), ':',  MONTH(assigned_at) , ':' ,  DAY(assigned_at) , ' ',  HOUR(assigned_at) , ':00') AS timerecord,
					COUNT(*) as total_count
			FROM 
				codes
			WHERE
				(assigned_at >= '2017-10-18 20:00:00' AND
				assigned_at < DATE_ADD('2017-10-18 20:01:52', INTERVAL 7 DAY)) AND
				type = '".$queryType."'
			GROUP BY 
				YEAR(assigned_at), 
				MONTH(assigned_at),
				DAY(assigned_at),
				HOUR(assigned_at)		
		");
		
		//Checking if QuerySting is provided or not...
		if (null !== $request->input('type')) {
			if (count($codes) > 0) {
				$returnResult['code'] = '200';
				$returnResult['count'] = count($codes);
				$returnResult['data'] = $codes;
			} else {
				$returnResult['code'] = '300';
				$returnResult['count'] = count($codes);
				$returnResult['data'] = 'No Results Found.';
			}
		} else {
				$returnResult['code'] = '404';
				$returnResult['error'] = "missing 'type'";
				$returnResult['data'] = 'No Results Found.';
		}
		
		
		//Encoding result array to JSON String...
		$codeJson = json_encode($returnResult);

		//Displaying result via View so that I can test out different formats...
		return view("api", compact('codeJson'));
	}
}
