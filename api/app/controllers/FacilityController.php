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
        $facilityData = Input::get('facility');
        $contactData = Input::get('contacts');
        $equipmentData = Input::get('equipment');
        
        $facility = new Facility();
        $facility->name = $facilityData['name'];
        $facility->institution = $facilityData['institution'];
        $facility->city = $facilityData['city'];
        $facility->province = $facilityData['province'];
        $facility->website = $facilityData['website'];
        $facility->description = $facilityData['description'];
        $facility->save();
        
        foreach($contactData as $c) {
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
        
        foreach($equipmentData as $e) {
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
        $facilityData = Input::get('facility');
        $contactData = Input::get('contacts');
        $equipmentData = Input::get('equipment');
        
        $facility = Facility::find($id);
        $facility->name = $facilityData['name'];
        $facility->institution = $facilityData['institution'];
        $facility->city = $facilityData['city'];
        $facility->province = $facilityData['province'];
        $facility->website = $facilityData['website'];
        $facility->description = $facilityData['description'];
        $facility->save();
        
        $contacts = Contact::where('facility_id', '=', $id)->get();
        $equipment = Equipment::where('facility_id', '=', $id)->get();
        
        foreach($contactData as $c) {
            $contact = $contacts->find($c['id']);
            $contact->firstName = $c['firstName'];
            $contact->lastName = $c['lastName'];
            $contact->email = $c['email'];
            $contact->telephone = $c['telephone'];
            $contact->position = $c['position'];
            $contact->website = $c['website'];
            $contact->save();
        }
        
        foreach($equipmentData as $e) {
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
        
    }
}