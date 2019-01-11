<?php

namespace App\Http\Controllers;

use App\User;
use App\Directory;
use App\Http\Requests\DirectoryIndexRequest;

class DirectoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DirectoryIndexRequest $request)
    {
        $user = $request->user();

        // This is a hack to allow local environment development
        if (env('APP_ENV') == "local" && !$user){
          $user = User::whereEmail('afred@scienceatlantic.ca')->first();
        }

        // Administrator has access to all directories
        if ($user->is_administrator) {
            return $this->pageOrGet(Directory::query());
        }

        // Editors have access to some directories
        return $this->pageOrGet($user->directories());
    }
}
