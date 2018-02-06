<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $role = Role
            ::whereIn('name', $request->roles ?: [])
            ->orderBy('level', 'desc')
            ->first();

        // Subscriber is default role.
        if (!$role) {
            $role = Role::findRole('Subscriber');
        }

        $user = new User();
        $user->role_id = $role->id;
        $user->wp_home = $request->wpHome;
        $user->wp_user_id = $request->wpUserId;
        $user->wp_username = $request->wpUsername;
        $user->first_name = $request->firstName;
        $user->last_name = $request->lastName;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(UserRequest $request, $id)
    {
        if ($request->is_wp_id) {
            if (!($user = User::findByWpIdAndHome($id, $request->wpHome))) {
                abort(404);
            }
        } else {
            $user = User::findOrFail($id);
        }

        return $user;
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
        if ($request->is_wp_id) {
            if (!($user = User::findByWpIdAndHome($id, $request->wpHome))) {
                abort(404);
            }
        } else {
            $user = User::findOrFail($id);
        }

        $role = Role
            ::whereIn('name', $request->roles ?: [])
            ->orderBy('level', 'desc')
            ->first();
        
        $user->role_id = $role ? $role->id : $user->role_id;
        $user->first_name = $request->firstName;
        $user->last_name = $request->lastName;
        $user->email = $request->email ?: $user->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->update();

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserRequest $request, $id)
    {
        //
    }

    public function isUsernameUnique(UserRequest $request)
    {

    }

    public function isEmailUnique(UserRequest $request)
    {

    }
}
