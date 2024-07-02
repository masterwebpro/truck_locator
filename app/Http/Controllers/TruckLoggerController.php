<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TruckLoggerModel;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TruckLoggerController extends Controller
{
    public function justAnExample()
    {
        return [
            'msg' => 'It works!'
        ];
    }

    public function store(Request $request) {
 
        $validator = Validator::make($request->all(), [
            'vehicle_id' => "required",
            'date_stamp' => "required",
            'latitude' => "required",
            'longitude' => "required",
            'direction' => "required",
            'speed' => "required",
        ],[
            'vehicle_id' => "Vehicle Id is required",
            'date_stamp' => "Date Stamp is required",
            'latitude' => "Latitude is required",
            'longitude' => "Longitude is required",
            'direction' => "Direction is required",
            'speed' => "Speed is required",
        ]);


        if ($validator->fails()) {
            return response()->json(['success'  => false, 'error' => $validator->errors()]);
        }
        DB::beginTransaction();
        
        try {

            $data = [
                'vehicle_id' => $request->vehicle_id,
                'api_key'    => $request->header('x-api-key'),
                'date_stamp' => $request->date_stamp,
                'latitude'  => $request->latitude,
                'longitude'  => $request->longitude,
                'direction'  => $request->direction,
                'speed'      => $request->speed,
                'updated_at'  => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            $vehicle_log = TruckLoggerModel::create($data);

            DB::commit();
            return response()->json([
                'success'  => true,
                'message' => 'Saved successfully!',
                'data'    => $vehicle_log,
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success'  => false,
                'message' => 'Unable to process request. Please try again.',
                'data'    => $e->getMessage()
            ]);
        }

    }

    public function insertMultiple(Request $request) {

        $validator = Validator::make($request->all(), [
            
            '*.vehicle_id' => "required",
            '*.date_stamp' => "required",
            '*.latitude' => "required",
            '*.longtitude' => "required",
            '*.direction' => "required",
            '*.speed.*' => "required",
        ]
        // ,[
        //    '*.vehicle_id' => "Vehicle is required",
        //     '*.date_stamp' => "Date Stamp is required",
        //     '*.latitude' => "Latitude is required",
        //     '*.longtitude' => "Longtitude is required",
        //     '*.direction' => "Direction is required",
        //     '*.speed' => "Speed is required",
        // ]
    );


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        DB::beginTransaction();
        
        try {
            $data = [];
            for($x=0; $x < count($request->all()); $x++) {
                $data[] = [
                    'vehicle_id' => $request[$x]['vehicle_id'],
                    'api_key'    => $request->header('x-api-key'),
                    'date_stamp' => $request[$x]['date_stamp'],
                    'latitude'   => $request[$x]['latitude'],
                    'longtitude' => $request[$x]['longtitude'],
                    'direction'  => $request[$x]['direction'],
                    'speed'      => $request[$x]['speed'],
                    'updated_at'  => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }

            if($data) {
                $vehicle_log = TruckLoggerModel::insert($data);
                DB::commit();
                    return response()->json([
                        'success'  => true,
                        'message' => 'Saved successfully!',
                    ]);
            } else  {
                DB::rollback();
                return response()->json([
                    'success'  => false,
                    'message' => 'Unable to process request. Please try again.',
                ]);
            }

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success'  => false,
                'message' => 'Unable to process request. Please try again.',
                'data'    => $e->getMessage()
            ]);
        }

    }
}
