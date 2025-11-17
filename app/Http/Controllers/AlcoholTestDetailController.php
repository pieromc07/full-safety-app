<?php

namespace App\Http\Controllers;

use App\Models\AlcoholTestDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AlcoholTestDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AlcoholTestDetail $alcoholTestDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AlcoholTestDetail $alcoholTestDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AlcoholTestDetail $alcoholTestDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, AlcoholTestDetail $alcoholTestDetail)
    {
        try {
            $alcoholTestDetail->delete();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['message' => 'Detalle eliminado correctamente'], 200);
            }

            return redirect()->back()->with('success', 'Detalle eliminado correctamente');
        } catch (\Exception $e) {
            Log::error('Error deleting AlcoholTestDetail: ' . $e->getMessage());

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['message' => 'No se pudo eliminar el detalle'], 500);
            }

            return redirect()->back()->with('error', 'No se pudo eliminar el detalle');
        }
    }
}
