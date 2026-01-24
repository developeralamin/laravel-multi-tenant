<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PersonRequest;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PersonController extends Controller
{
  /**
     * Summary of index
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $people = Person::latest('id')->get();
        
        return PersonResource::collection($people);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PersonRequest $request)
    {
        $image = $request->file('photo');
        $data = $request->all();
        if ($image) {
            $data['photo'] = Storage::putFile('person/photo', $image);
            $data['thumb'] = Storage::putFile('person/thumb', $image);
        }
        $person = Person::create($data);
    
        return new PersonResource($person);
    }

    /**
     * Summary of show
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $person = Person::find($id);
        
        return new PersonResource($person);
    }

    /**
     * Summary of update
     * @param PersonRequest $request
     * @param mixed $id
     * @return PersonResource
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $product = Person::findOrFail($id);
        if ($request->file('photo')) {
            if ($product->photo && $product->thumb) {
                Storage::delete([$product->photo, $product->thumb]);
            }
            $data['photo'] = Storage::putFile('product/photo', $request->file('photo'));
            $data['thumb'] = Storage::putFile('product/thumb', $request->file('photo'));
        }
        $product->update($data);

        return $this->success('Person Updated Successfully');

    }

    /**
     * Summary of destroy
     * @param mixed $id
     * @return void
     */
    public function destroy($id)
    {
        $person = Person::find($id);
        if ($person->photo && $person->thumb) {
            Storage::delete([$person->photo, $person->thumb]);
        }
        $person->delete();
    }

}