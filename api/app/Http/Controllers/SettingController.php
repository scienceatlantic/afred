<?php

namespace App\Http\Controllers;

// Models.
use App\Setting;

// Requests.
use Illuminate\Http\Request;
use App\Http\Requests\SettingRequest;

class SettingController extends SettingBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SettingRequest $request)
    {
        if ($name = $request->name) {
            $name = is_array($name) ? $name : [$name];
            return Setting::whereIn('name', $name)->get()->keyBy('name');
        }
        return Setting::all()->keyBy('name');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */     
    public function update(SettingRequest $request, $id)
    {
        return $this->updateRecord($request, Setting::findOrFail($id));
    }
}
