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

        if (isset($config)) {
            if($config->value == 0){
                return $next($request);
            }
        }
        return response()->json([
            'status' => false,
            'message' => "This application is currently under maintenance, please try again later"
            ], 200);
        
    }
}
