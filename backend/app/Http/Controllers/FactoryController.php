<?php

    namespace App\Http\Controllers;


    use App\Models\Tree;
    use Illuminate\Http\Request;

    class FactoryController extends Controller
    {
        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct()
        {
            //
        }

        public function index(Request $request)
        {
            // Working directly with response here. All validations are in middleware
            $key = $request['tree_key'];

            $status = 200;
            $message = 'Factories tree was retrieved successfuly';
            $factories = Tree::where('hash', $key)->firstOrFail()->toArray();

            $payload = [
                'status' => $status,
                'message' => $message,
                'payload' => $factories
            ];

            return response($payload)->setStatusCode($status, $message);
        }

    }
