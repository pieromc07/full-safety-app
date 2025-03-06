<?php

namespace App\Http\Controllers;

use App\Models\GPSControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GPSControlController extends Controller
{


  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    try {
      DB::beginTransaction();

      $cgps = $request->input('controlgps');

      if ($cgps == null) {
        return response()->json([
          'message' => 'Control GPS es requerido',
        ], 400);
      }

      $cgps = json_decode($cgps, true);

      $newGPS = new GPSControl();
      $newGPS->date = $cgps['date'];
      $newGPS->hour = $cgps['hour'];
      $newGPS->checkpoint_id = $cgps['checkpoint_id'];
      $newGPS->id_supplier_enterprises = $cgps['id_supplier_enterprises'];
      $newGPS->id_transport_enterprises = $cgps['id_transport_enterprises'];
      $newGPS->option = $cgps['option'];
      $newGPS->state = $cgps['state'];
      $newGPS->observation = $cgps['observation'];
      $newGPS->photo_one = $this->saveGPSControlImage($cgps['photo_one_base64'], 'photo_one');
      $newGPS->photo_two = $this->saveGPSControlImage($cgps['photo_two_base64'], 'photo_two');
      $newGPS->save();
      DB::commit();
      return response()->json([
        'status' => true,
        'message' => 'Control GPS creado con éxito',
      ], 201);
    } catch (\Exception $e) {
      DB::rollBack(); // Rollback transaction in case of error
      return response()->json([
        'status' => false,
        'message' => 'Error al crear el Control GPS',
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
  private function saveGPSControlImage(?string $base64Image, string $type): ?string
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
      if (Storage::disk('public')->put('control/' . $fileName, $imageData)) {
        return 'control/' . $fileName;
      }

      return null;
    } catch (\Exception $e) {
      // En caso de error, devolver null
      return null;
    }
  }

  public function index(Request $request)
  {
    $controls = GPSControl::paginate(10);
    return view('control.index', compact('controls'));
  }

  /**
   * Display the specified resource.
   */
  public function show(GPSControl $control)
  {
    return view('control.show', compact('control'));
  }
}
