<?php

namespace App\Http\Controllers;

use App\Models\Factory;
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

    public function index()
    {
        return response()->json(Factory::all());
    }
}
