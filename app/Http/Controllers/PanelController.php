<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Validator;

use App\Models\Panel;

class PanelController extends Controller
{
    public function index()
    {
        return Panel::all();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Panel::$fieldValidations);
        if ($validator->fails()) {
            return Response::json($validator->errors()->all(), 400);
        }
        return Panel::create($request->all());
    }

    public function update(Request $request, Panel $panel)
    {
        $validator = Validator::make($request->all(), Panel::$fieldValidations);
        if ($validator->fails()) {
            return Response::json($validator->errors()->all(), 400);
        }
        $panel->update($request->all());

        return response()->json($panel, 200);
    }

    public function showOneHourElectricityByPanelId($id)
    {
        $panel = Panel::findOrFail($id);

        return response()->json($panel->oneHourElectricities, 200);
    }
}
