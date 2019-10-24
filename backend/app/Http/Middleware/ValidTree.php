<?php


    namespace App\Http\Middleware;

    use App\Models\Tree;
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
            // Experiments with Auth methods
            $key =  $request->hasHeader('Authorization')?
                $request->header('Authorization'):$request->only('tree_key');
            $key = is_array($key) && array_key_exists('tree_key', $key)?$key['tree_key']:$key;

            if(!$key){
                $payload = [
                    'status' => 400,
                    'message' => 'Authorization header not found'
                ];
                return response()->json($payload, $payload['status'] );
            }

            if (!Tree::where('key', $key)->first()) {
                $payload = [
                    'status' => 401,
                    'message' => 'Wrong authorization'
                ];
                return response()->json($payload, $payload['status'] );
            }

            return $next($request);

        }
    }
