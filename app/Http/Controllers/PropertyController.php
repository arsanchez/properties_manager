<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Type;
use ImageResize;
use App\Http\Controllers\Session;

class PropertyController extends Controller
{
    public function index()
    {   
        $types = json_encode(Type::select('id','title')->get()->toArray());
        return view('home', ['types' => $types]);
    }

    public function addEdit(Request $request) 
    {
        if ($request->id > 0) {
            $property = Property::findOrFail($request->id);
        } else {
            $property = new Property;
        }

        $types = json_encode(Type::select('id','title')->get()->toArray());
        return view('edit', ['property' => $property, 'types' => $types]);
    }

    public function save($id, Request $request) 
    {
        if ($id > 0) {
            $result = Property::find($request->id);
           
        } else {
            $result = Property::create();
        }

        $result->county = $request->county;
        $result->country = $request->country;
        $result->town = $request->town;
        $result->description = $request->description;
        $result->address = $request->address;
        $result->num_bedrooms = $request->num_bedrooms;
        $result->num_bathrooms = $request->num_bathrooms;
        $result->price = $request->price;
        $result->property_type_id = $request->property_type_id;
        $result->type = $request->listing_type;

        // Checking for the image
        $this->validate($request, [
            'file' => 'required',
            'file.*' => 'mimes:jpeg,jpg,gif,png'
        ]);
  
        $image = $request->file('file');
        $input['imagename'] = time().'.'.$image->extension();
     
        $destinationPath = public_path('/thumbnail');
        $img = ImageResize::make($image->path());
        $img->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['imagename']);
        $result->image_thumbnail = $input['imagename'];
   
        $destinationPath = public_path('/image');
        $image->move($destinationPath, $input['imagename']);
        $result->image_full = $input['imagename'];

        return $result->save();
    }

    public function getProperties(Request $request)
    {
 
        // Basic paging to be implemented
        $lines_per_page  = 500;
        $page = $request->page;
        $offset = ($page - 1) * $lines_per_page;

        // Getting the top 500 properties 
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
        $property = Property::findOrFail($request->id);

        return $property->delete();
    }

}
