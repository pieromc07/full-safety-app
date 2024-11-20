<?php

namespace App\Http\Controllers;

use App\Models\AlcoholTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AlcoholTestController extends Controller
{
  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    try {
      DB::beginTransaction();

      $test = $request->input('alcoholtest');

      if ($test == null) {
        return response()->json([
          'message' => 'Prueba de Alcohol es requerido',
        ], 400);
      }

      $test = json_decode($test, true);

      $newTest = new AlcoholTest();
      $newTest->date = $test['date'];
      $newTest->hour = $test['hour'];
      $newTest->checkpoint_id = $test['checkpoint_id'];
      $newTest->supplier_enterprise_id = $test['supplier_enterprise_id'];
      $newTest->transport_enterprise_id = $test['transport_enterprise_id'];
      $newTest->employee_id = $test['employee_id'];
      $newTest->result = $test['result'];
      $newTest->state = $test['state'];
      $newTest->photo_one = $this->saveTestAlcoholImage($test['photo_one_base64'], 'photo_one');
      $newTest->photo_two = $this->saveTestAlcoholImage($test['photo_two_base64'], 'photo_two');
      $newTest->save();
      DB::commit(); // Commit transaction if everything is successful
      return response()->json([
        'status' => true,
        'message' => 'Prueba de Alcohol creado con éxito',
      ], 201);
    } catch (\Exception $e) {
      DB::rollBack(); // Rollback transaction in case of error
      return response()->json([
        'status' => false,
        'message' => 'Error al crear el Pausa Activa',
        'error' => $e->getMessage()
      ], 500);
    }
  }


  /**
   * Decodifica y guarda la imagen en base64 en el disco.
   *
   * @param string|null $base64Image Imagen en formato base64.
   * @param int $inspectionId ID de la inspección asociada.
   * @param string $type Tipo de evidencia (evidence_one, evidence_two, etc.).
   * @return string|null Ruta de la imagen guardada o null si no se guarda.
   */
  private function saveTestAlcoholImage(?string $base64Image, string $type): ?string
  {
    if (!$base64Image) {
      return null;
    }

    try {
      // Eliminar la cabecera del base64
      $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
      $base64Image = str_replace(' ', '+', $base64Image);
      $imageData = base64_decode($base64Image);

      // Generar nombre de archivo único
      $fileName = time() . "_{$type}.png";
      if (Storage::disk('public')->put('test/' . $fileName, $imageData)) {
        return 'test/' . $fileName;
      }

      return null;
    } catch (\Exception $e) {
      // En caso de error, devolver null
      return null;
    }
  }


  public function index(Request $request)
  {
    $tests = AlcoholTest::paginate(10);
    return view('test.index', compact('tests'));
  }

    /**
   * Display the specified resource.
   */
  public function show(AlcoholTest $test)
  {
    return view('test.show', compact('test'));
  }
}
