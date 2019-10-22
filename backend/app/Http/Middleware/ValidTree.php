<?php


    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;

    class ValidTree
    {
        /**
         * Handle an incoming request.
         *
         * @param  Request  $request
         * @param  Closure  $next
         * @return mixed
         */

        public function handle($request, Closure $next)
        {

            $key = $request['tree_key'];

            if(!$key){
                $payload = [
                    'status' => 400,
                    'message' => 'Authorization header not found'
                ];
                return response()->json($payload, $payload['status'] );
            }

            if ($key !== 'cf23df2207d99a74fbe169e3eba035e633b65d94') {
                $payload = [
                    'status' => 401,
                    'message' => 'Wrong authorization'
                ];
                return response()->json($payload, $payload['status'] );
            }

            return $next($request);

        }
    }
