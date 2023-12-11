<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Student;
use App\Traits\HttpResp;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{

    use HttpResp;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $request)
    {
        try {
            $image_64 = $request->photo;
            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
            $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
            $image = str_replace($replace, '', $image_64);
            $image = str_replace(' ', '+', $image);
            $imageName = time() . '.' . $extension;
            Storage::disk('public')->put($imageName, base64_decode($image));
            $image64 = $request->photo_diplome;
            $extension1 = explode('/', explode(':', substr($image64, 0, strpos($image64, ';')))[1])[1];
            $replace1 = substr($image64, 0, strpos($image64, ',') + 1);
            $image1 = str_replace($replace1, '', $image64);
            $image1 = str_replace(' ', '+', $image1);
            $imageName1 = 'diplome' . time() . '.' . $extension1;
            Storage::disk('public')->put($imageName1, base64_decode($image1));
            $student =  Student::create([
                'nom' => $request->nom,
                'prenoms' => $request->prenom,
                'civilite' => $request->civilite,
                'departement' => $request->departement,
                'ecole' => $request->ecole,
                'numero_gtin' => $request->num_gtin,
                'id_system' => $request->id_system,
                'photo' => $imageName,
                'photo_diplome' => $imageName1
            ]);
            return $this->success(200, 'created succesfully', $student);
        } catch (\Exception $th) {
            throw new Error($th->getMessage());
        }
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
