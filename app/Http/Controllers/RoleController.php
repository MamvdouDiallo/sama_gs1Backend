<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Traits\HttpResp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    use HttpResp;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();

        return $this->success(200, "liste des roles", $roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'libelle' => 'string|required|unique:roles|min:4',
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        $classe = Role::create($request->all());
        return $this->success(200, '', $classe);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
