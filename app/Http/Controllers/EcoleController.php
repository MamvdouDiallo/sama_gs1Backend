<?php

namespace App\Http\Controllers;

use App\Http\Requests\EcoleRequest;
use App\Models\Ecole;
use App\Traits\HttpResp;
use Illuminate\Http\Request;

class EcoleController extends Controller
{

    use HttpResp;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ecoles = Ecole::all();
        return $this->success(200, "Liste des ecoles", $ecoles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EcoleRequest $request)
    {
        $ecole = Ecole::create($request->all());
        return $this->success(200, "Ecole crée avec succés", $ecole);
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
