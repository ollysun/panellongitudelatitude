<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Validator;

use App\Models\OneHourElectricity;
use App\Models\Panel;

class OneHourElectricityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $panel_serial = $request->query('panel_serial');
        $panel = Panel::where('serial', $panel_serial)->firstOrFail();
        return OneHourElectricity::where('panel_id', $panel->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $panelRequest = $this->requestFormat($request);
        $validator = Validator::make($panelRequest, OneHourElectricity::$fieldValidations);
        if ($validator->fails()) {
            //return Response::json($validator->errors()->all(), 400);
            return response()->json('error', 400);

        }
        return OneHourElectricity::create($panelRequest);
    }

    public function requestFormat(Request $request)
    {
        $panel = Panel::where('serial', $request->panel_serial)->first();
        $params['panel_id'] = $panel->id;
        $params['kilowatts'] = $request->kilowatts;
        $params['hour'] = $request->hour;
        return $params;
    }


    public function update(Request $request, OneHourElectricity $oneHourElectricity)
    {
        $validator = Validator::make($request->all(), OneHourElectricity::$fieldValidations);
        if ($validator->fails()) {
            return Response::json($validator->errors()->all(), 400);
        }

        $oneHourElectricity->update($request->all());
        return response()->json($oneHourElectricity->toArray(), 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showElectricityByPanelId($id)
    {
        $oneHourElectricity = OneHourElectricity::where('panel_id', $id)->get();
        if (is_null($oneHourElectricity) || !is_numeric($id)) {
            return response()->json('error', 400);
        }
        return response()->json($oneHourElectricity->toArray());
    }




}
