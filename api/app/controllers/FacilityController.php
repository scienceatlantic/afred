<?php

class FacilityController extends \BaseController {
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Facility::all(); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $facility = new Facility();
        $facility->name = Input::get('name');
        $facility->institution = Input::get('institution');
        $facility->city = Input::get('city');
        $facility->province = Input::get('province');
        $facility->website = Input::get('website');
        $facility->description = Input::get('description');
        $facility->save();
        
        foreach(Input::get('contacts') as $c) {
            $contact = new Contact();
            $contact->facilityId = $facility->id;
            $contact->firstName = $c['firstName'];
            $contact->lastName = $c['lastName'];
            $contact->email = $c['email'];
            $contact->telephone = $c['telephone'];
            $contact->position = $c['position'];
            $contact->website = $c['website'];
            $contact->save();
        }
        
        foreach(Input::get('equipment') as $e) {
            $equipment = new Equipment();
            $equipment->facilityId = $facility->id;
            $equipment->name = $e['name'];
            $equipment->purpose = $e['purpose'];
            $equipment->specifications = $e['specifications'];
            $equipment->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return Facility::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $facility = Facility::find($id);
        $facility->name = Input::get('name');
        $facility->institution = Input::get('institution');
        $facility->city = Input::get('city');
        $facility->province = Input::get('province');
        $facility->website = Input::get('website');
        $facility->description = Input::get('description');
        $facility->isActive = Input::has('isActive') ? Input::get('isActive') : $facility->isActive;
        $facility->save();
        
        $contacts = Contact::where('facility_id', '=', $id)->get();
        foreach(Input::get('contacts') as $c) {
            $contact = $contacts->find($c['id']);
            $contact->firstName = $c['firstName'];
            $contact->lastName = $c['lastName'];
            $contact->email = $c['email'];
            $contact->telephone = $c['telephone'];
            $contact->position = $c['position'];
            $contact->website = $c['website'];
            $contact->save();
        }
        
        $equipment = Equipment::where('facility_id', '=', $id)->get();
        foreach(Input::get('equipment') as $e) {
            $equipment = $equipment->find($e['id']);
            $equipment->name = $e['name'];
            $equipment->purpose = $e['purpose'];
            $equipment->specifications = $e['specifications'];
            $equipment->save();
        }    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Facility::find($id)->delete();
    }
}