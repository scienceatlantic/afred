<?php

namespace App\Http\Controllers;

// Misc.
use Hash;

// Model.
use App\User;

// Requests.
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    function __construct(Request $request)
    {
        parent::__construct($request);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserRequest $request)
    {
        $u = User::with('roles')->orderBy('firstName', 'asc');
        return $this->pageOrGet($u);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $now = $this->now();
        $user = new User();
        $user->firstName = $request->firstName;
        $user->lastName = $request->lastName;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->isActive = $request->isActive;
        $user->dateCreated = $now;
        $user->dateUpdated = $now;
        $user->save();
        $user->roles()->attach($request->roles);
        return AuthController::format(false, $user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(UserRequest $request, $id)
    {
        return AuthController::format(false, User::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        // If updating user password.
        if ($request->password) {
            $user->password = Hash::make($request->password);
        } 
        // If updating everything else.
        else {
            $user->firstName = $request->firstName;
            $user->lastName = $request->lastName;
            $user->email = $request->email;
            $user->isActive = $request->isActive;
            $user->roles()->sync($request->roles);
        }
        $user->dateUpdated = $this->now();
        $user->save();
        return AuthController::format(false, $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserRequest $request, $id)
    {
        $u = User::findOrFail($id);
        $deletedUser = $u->toArray();
        $u->delete();
        return $this->toCcArray($deletedUser);
    }
}
