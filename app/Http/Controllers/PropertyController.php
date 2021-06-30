<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Type;

class PropertyController extends Controller
{
    public function index()
    {   
        $types = json_encode(Type::select('id','title')->get()->toArray());
        return view('home', ['types' => $types]);
    }

    public function getProperties(Request $request)
    {

    }

}
