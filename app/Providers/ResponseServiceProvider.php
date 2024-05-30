<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ( $message = "", $status = 200) {
            return response()->json([
               'status' =>'success',
               'message' => $message,
            ], $status);
        });

        Response::macro('error', function ($message = "Something went wrong", $status = 500) {
            return response()->json([
               'status' =>'error',
               'message' => $message,

            ], $status);
        });

        Response::macro('notFound', function ($message = 'Resource not found') {
            return response()->json([
                'status' => 'error',
                'message' => $message,
            ], 404);
        });

        Response::macro('methodNotAllowed', function ($message = 'Method not allowed') {
            return response()->json([
                'status' => 'error',
                'message' => $message,
            ], 405);
        });
    }
}
