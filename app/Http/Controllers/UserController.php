<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\FormEntry;
use App\FormEntryStatus as Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserDestroyRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $role = Role
            ::whereIn('name', $request->roles ?: [])
            ->orderBy('level', 'desc')
            ->first();

        // Update user if the email already exists
        if($existing_user = User::where('email', $request->email)->first()){

          if ($request->has('wpUsername')) {
              $existing_user->wp_username = $request->wpUsername;
          }
          if ($request->has('email')) {
              $existing_user->email = $request->email;
          }
          if ($request->has('password')) {
              $existing_user->password = Hash::make($request->password);
          }
          $existing_user->update();

          return $existing_user;
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        if (filter_var($id, FILTER_VALIDATE_EMAIL)) {
            $user = User::findByEmailOrFail($id);
        } else {
            $user = User::findOrFail($id);
        }

        if ($request->has('roles')) {
            $user->role_id = Role
                ::whereIn('name', $request->roles)
                ->orderBy('level', 'desc')
                ->first()
                ->id;
        }
        if ($request->has('firstName')) {
            $user->first_name = $request->firstName;
        }
        if ($request->has('lastName')) {
            $user->last_name = $request->lastName;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('password')) {
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
    public function destroy(UserDestroyRequest $request, $id)
    {
        if (filter_var($id, FILTER_VALIDATE_EMAIL)) {
            $user = User::findByEmailOrFail($id);
        } else {
            $user = User::findOrFail($id);
        }

        $deletedUser = $user->toArray();
        $user->delete();

        return $deletedUser;
    }

    public function isUsernameUnique(UserRequest $request)
    {

    }

    public function isEmailUnique(UserRequest $request)
    {

    }

    public function listings(Request $request, $id)
    {
      $relationships = [
          'status',
          'form.directory',
          'formsAttachedTo.directory',
          'author',
          'reviewer',
          'primaryContact',
          'listings'
      ];

      if (filter_var($id, FILTER_VALIDATE_EMAIL)) {
          $user = User::findByEmailOrFail($id);
      } else {
          $user = User::findByWpId($id);
      }

      return [ 'result' => FormEntry::where('author_user_id', $user->id)->with($relationships)->get()];
    }

    /**
    * Returns relevant metrics for current searches
    */
    public function getCurrentSearchMetrics($formEntryResults)
    {

      $metrics = [];
      $count = 0;
      $status_names = [];
      $results = $formEntryResults->paginate(1000);
      foreach($results as $formEntry){
        $count++;
        if(!array_key_exists($formEntry->form_entry_status_id, $status_names)) {
          $status = Status::findStatusById($formEntry->form_entry_status_id);
          $status_names[$status->id] = strtolower($status->name);
        };
        if(!array_key_exists($status_names[$formEntry->form_entry_status_id], $metrics)) {
          $metrics[$status_names[$formEntry->form_entry_status_id]] = 0;
        };
        $metrics[$status_names[$formEntry->form_entry_status_id]] += 1;
      }
      $metrics['count'] = $count;
      return $metrics;

    }
}
