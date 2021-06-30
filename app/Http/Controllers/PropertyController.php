<?php

namespace App\Http\Controllers;

use DB;
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
 
        // Basic paging
        $lines_per_page  = 100;
        $page = $request->page;
        $offset = ($page - 1) * $lines_per_page;

        // Getting the properties 
        $query = DB::table('properties')
                    ->leftJoin('property_types', 'properties.property_type_id', '=', 'property_types.id')
                    ->select('properties.id','properties.county','properties.country','properties.town','properties.description','properties.address',
                    'properties.image_thumbnail','properties.latitude','properties.longitude','properties.num_bedrooms','properties.num_bathrooms',
                    'properties.price','properties.type', 'property_types.title as property_type')
                    ->skip($offset)->take($lines_per_page)
                    ->whereNull('properties.deleted_at');

        // Applyinh the filters
        if ($request->name != '') {
            $query->where('town', 'LIKE','%'.$request->name.'%');
        }

        if ($request->bedrooms != '') {
            $query->where('num_bedrooms', $request->bedrooms);
        }

        if ($request->price > 0) {
            $query->where('price', $request->price);
        }

        if ($request->listing_type != '') {
            $query->where('type', $request->listing_type);

        }

        if ($request->type > 0) {
            $query->where('property_type_id', $request->type);
        }

        $properties = $query->get();

        return response()->json($properties); 
    }

    public function delete(Request $request) {
        $property = Property::find($request->id);

        return $property->delete();
    }

}
