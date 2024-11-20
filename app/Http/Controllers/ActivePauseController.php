<?php

namespace App\Http\Controllers;

use App\Models\ActivePause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ActivePauseController extends Controller
{
  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    try {
      DB::beginTransaction();

      $pauseactive = $request->input('pauseactive');

      if ($pauseactive == null) {
        return response()->json([
          'message' => 'Pausa Activa es requerido',
        ], 400);
      }

      $pauseactive = json_decode($pauseactive, true);

      $newPauseActive = new ActivePause();
      $newPauseActive->date = $pauseactive['date'];
      $newPauseActive->hour = $pauseactive['hour'];
      $newPauseActive->checkpoint_id = $pauseactive['checkpoint_id'];
      $newPauseActive->supplier_enterprise_id = $pauseactive['supplier_enterprise_id'];
      $newPauseActive->transport_enterprise_id = $pauseactive['transport_enterprise_id'];
      $newPauseActive->participants = $pauseactive['participants'];
      $newPauseActive->photo_one = $this->savePauseActiveImage($pauseactive['photo_one_base64'], 'photo_one');
      $newPauseActive->photo_two = $this->savePauseActiveImage($pauseactive['photo_two_base64'], 'photo_two');
      $newPauseActive->save();
      DB::commit(); // Commit transaction if everything is successful
      return response()->json([
        'status' => true,
        'message' => 'Pausa Activa creado con éxito',
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
  private function savePauseActiveImage(?string $base64Image, string $type): ?string
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
      if (Storage::disk('public')->put('pauseactive/' . $fileName, $imageData)) {
        return 'pauseactive/' . $fileName;
      }

      return null;
    } catch (\Exception $e) {
      // En caso de error, devolver null
      return null;
    }
  }

  public function index(Request $request)
  {
    $actives = ActivePause::paginate(10);
    return view('active.index', compact('actives'));
  }

    /**
   * Display the specified resource.
   */
  public function show(ActivePause $active)
  {
    return view('active.show', compact('active'));
  }
}
