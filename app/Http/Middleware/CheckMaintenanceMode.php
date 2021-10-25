<?php

namespace App\Http\Middleware;

use App\Models\Configuration;
use Closure;
use Illuminate\Http\Request;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $config = Configuration::where('name', 'maintenance')->first();
        $message_err = "Sorry, Pastoral Hub is under maintenance, please try again later. If you need any urgent assistance, please email IFGF Global Team at secretariat@ifgf.global or via Whatsapp to +6281286373437";

        if (isset($config)) {
            if($config->value == 0){
                return $next($request);
            }
        }
        return response()->json([
            'status' => false,
            'message' => $message_err
            ], 200);
        
    }
}
