<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->getAll();
        if (!$data) {
            return response()->json([
                'message' => 'Database error',
                'status' => 'Error',
            ], 500);
        }
        return response()->json([
            'data' => $data,
            'message' => 'Data received success',
            'status' => 'Success'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->getById($id);
        if (!$data) {
            return response()->json([
                'message' => 'Data not found',
                'status' => 'Error',
            ], 404);
        }
        return response()->json([
            'data' => $data,
            'message' => 'Data received success',
            'status' => 'Success'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getAll()
    {
        return PaymentMethod::all();
    }

    public function getById($id)
    {
        return PaymentMethod::where('id_payment_method', $id)->first();
    }
}
