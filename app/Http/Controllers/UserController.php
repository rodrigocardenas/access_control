<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\PostUserRequest;
use App\Http\Requests\PostAccessLogRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::with('accessLogs.building')->filters($request);

        return UserResource::collection($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostUserRequest $request)
    {
        $user = User::create($request->all());

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => new UserResource($user), 'access_token' => $accessToken], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(PostUserRequest $request, User $user)
    {
        // $user = User::update($request->all());
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => new UserResource($user), 'access_token' => $accessToken], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'deleted'], 200);
    }

     /**
     * Store a new Access created resource in storage.
     *
     * @param  \Illuminate\Http\PostAccessLogRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAccess(PostAccessLogRequest $request, User $user)
    {
        $this->validateAccess($request, $user);

        $user->accessLogs()->create($request->all());

        return new UserResource($user);
    }

    /**
     * validate if is the same access type as user last record.
     *
     * @param  \Illuminate\Http\PostAccessLogRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function validateAccess(Request $request, User $user)
    {
        if (!isset($user->latestAccess->building_id)) {
            return true;
        }
        
        if ($user->latestAccess->building_id == $request->building_id && $user->latestAccess->type == $request->type) {
            abort(response()->json(['message' => 'not allowed the same access type as your last record'], 422));
        }

        return true;
    }
}
