<?php

    namespace App\Http\Controllers;


    use App\Models\Tree;
    use Exception;
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
            // Experiments with Auth methods
            $key = $request->hasHeader('Authorization')?$request->header('Authorization'):$request->only('tree_key');

            $status = 200;
            $message = 'Factories tree was retrieved successfuly';
            $factories = Tree::where('key', $key)->firstOrFail()->toArray();

            $payload = [
                'status' => $status,
                'message' => $message,
                'payload' => $factories
            ];

            return response($payload)->setStatusCode($status, $message);
        }

        public function save(Request $request)
        {
            // Tree key is validated in the ValidTree middleware
            $this->validate($request, [
                'payload' => 'required'
            ]);

            $data = $request->only('tree_key', 'payload');

            $status = 404;
            $message = 'Requested data not found';

            try {
                $factory = Tree::where('key', $data['tree_key'])->firstOrFail();

                if ($factory) {
                    $factory->data = is_array($data['payload'])?json_encode($data['payload']):$data['payload'];
                    $factory->save();
                    $message = 'Tree was updated successfully';
                    $status = 200;
                }

            } catch (Exception $e) {
                $factory = null;
                $message = $e->getMessage();
            }

            $payload = [
                'status' => $status,
                'message' => $message,
                'payload' => $factory
            ];

            return response($payload)->setStatusCode($status, $message);

        }
    }
