<?php

namespace App\Http\Middleware;

use Closure;
use Config;
use DB;
use Illuminate\Support\Facades\View;

class ChooseDatabase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        
        $dometic_hotel_id = $request->route('dometic_hotel_id'); // check route for hotel id
//        var_dump($dometic_hotel_id);
        if(empty($dometic_hotel_id)){
            // if empty check check session
            if(empty($dometic_hotel_id)){
                $dometic_hotel_id = $request->session()->get('dometic_hotel_id');
            }
        }else{
            
        }

        if(!empty($dometic_hotel_id)){
            
            $hotel_db = "dms_hotel_" . $dometic_hotel_id;
            Config::set('database.connections.mysql2.database', $hotel_db);
            
            // check for database connnetion
            $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
            $db = DB::select($query, [$hotel_db]);
            if(empty($db)){
                // just return to previous for now. Will want an error page.
                return redirect()->back()->with('error', "Hotel $dometic_hotel_id Database doesn't exist");
            }else{

            }
        }else{
            // if no hotel id redirect to error page
//            return \Redirect::route('no hotel error page');
        }
        
        return $next($request);
    }
}
