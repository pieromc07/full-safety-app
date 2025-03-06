<?php

namespace App\Http\Controllers;

use App\Models\DailyDialog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DailyDialogController extends Controller
{

  public function index(Request $request)
  {
    $dialogues = DailyDialog::paginate(10);
    return view('dialogue.index', compact('dialogues'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    try {
      DB::beginTransaction();

      $dialogue = $request->input('dialogue');

      if ($dialogue == null) {
        return response()->json([
          'message' => 'Dialogo diario es requerido',
        ], 400);
      }

      $dialogue = json_decode($dialogue, true);

      $newDialogue = new DailyDialog();
      $newDialogue->date = $dialogue['date'];
      $newDialogue->hour = $dialogue['hour'];
      $newDialogue->checkpoint_id = $dialogue['checkpoint_id'];
      $newDialogue->id_supplier_enterprises = $dialogue['id_supplier_enterprises'];
      $newDialogue->id_transport_enterprises = $dialogue['id_transport_enterprises'];
      $newDialogue->topic = $dialogue['topic'];
      $newDialogue->participants = $dialogue['participants'];
      $newDialogue->photo_one = $this->saveDialogueImage($dialogue['photo_one_base64'], 'photo_one');
      $newDialogue->photo_two = $this->saveDialogueImage($dialogue['photo_two_base64'], 'photo_two');
      $newDialogue->save();
      DB::commit(); // Commit transaction if everything is successful
      return response()->json([
        'status' => true,
        'message' => 'Dialogo diario creado con éxito',
      ], 201);
    } catch (\Exception $e) {
      DB::rollBack(); // Rollback transaction in case of error
      return response()->json([
        'status' => false,
        'message' => 'Error al crear el Dialogo diario',
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
  private function saveDialogueImage(?string $base64Image, string $type): ?string
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
      if (Storage::disk('public')->put('dialogue/' . $fileName, $imageData)) {
        return 'dialogue/' . $fileName;
      }

      return null;
    } catch (\Exception $e) {
      // En caso de error, devolver null
      return null;
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(DailyDialog $dialogue)
  {
    return view('dialogue.show', compact('dialogue'));
  }
}
