<?php

    namespace App\Http\Controllers;

    use App\Events\TreeUpdated;
    use App\Helpers\Sanitizer;
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
            $data = $request->only('tree_key', 'payload');

            $status = 400;
            $message = 'Tree data is in wrong format';

            // Tree key and payload presence are validated in the ValidTree middleware
            // Here need to make slightly deeper validations to check is payload has a right format
            $incomingPayloadValid = is_array($data['payload']) && array_key_exists('hash', $data['payload']) && array_key_exists('title', $data['payload']) && array_key_exists('hash', $data['payload']) && array_key_exists('children', $data['payload']);
            $incomingPayloadValid = $incomingPayloadValid && !empty($data['payload']['title']) && !empty($data['payload']['hash']) && !empty($data['payload']['children']);

            if ($incomingPayloadValid) {
                try {
                    $tree = Tree::where('key', $data['tree_key'])->firstOrFail();

                    if ($tree) {
                        $tree->data = json_encode(Sanitizer::cleanArray($data['payload']));
                        $tree->save();
                        $message = 'Tree was updated successfully';
                        $status = 200;
                    }

                    event(new TreeUpdated($tree));

                } catch (Exception $e) {
                    $tree = null;
                    $message = $e->getMessage();
                }
            }

            $payload = [
                'status' => $status,
                'message' => $message,
                'payload' => $tree
            ];

            return response($payload)->setStatusCode($status, $message);

        }
    }
