<?php

namespace App\Http\Controllers;

use App\Models\OneHourElectricity;
use Illuminate\Http\Request;

use App\Models\Panel;

class OneDayElectricityController extends Controller
{
    protected $sum;
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $panel = Panel::where('serial', $request->query('panel_serial'))->first();
        $oneDay = 24 * 60 * 60;

        $oneHour = OneHourElectricity::where('panel_id', $panel->id)->get()->toArray();
        $arrayColumnVal = array_column($oneHour,'kilowatts');
        $size = count($arrayColumnVal);

        foreach ($arrayColumnVal as $val)
        {
            $this->sum += $val;
        }
        $min = min($arrayColumnVal);
        $max = max($arrayColumnVal);
        $kilowattPerDay = $oneDay * $this->sum;
        $avg = $this->sum/$size;

        return [
            'day' => $kilowattPerDay,
            'sum' => $this->sum,
            'min' => $min,
            'max' => $max,
            'average' => $avg
        ];
    }
}
