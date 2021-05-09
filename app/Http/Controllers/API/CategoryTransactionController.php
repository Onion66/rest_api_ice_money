<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\CategoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryTransactionController extends Controller
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
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'message' => 'Validation Error',
                'status' => 'Error'
            ], 400);
        }
        $newId = $this->generateId();
        $response = $this->createById($newId, $request->name);
        if (!$response) {
            return response()->json([
                'message' => 'Database error',
                'status' => 'Error',
            ], 500);
        }
        return response()->json([
            'message' => 'Data insert success',
            'status' => 'Success'
        ], 200);
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
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'message' => 'Validation Error',
                'status' => 'Error'
            ], 400);
        }
        $response = $this->updateById($request->id, $request->name);
        if (!$response) {
            return response()->json([
                'message' => 'Data not found',
                'status' => 'Error',
            ], 404);
        }
        return response()->json([
            'message' => 'Data update success',
            'status' => 'Success'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'string']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'message' => 'Validation Error',
                'status' => 'Error'
            ], 400);
        }
        $response = $this->deleteById($request->id);
        if (!$response) {
            return response()->json([
                'message' => 'Data not found',
                'status' => 'Error',
            ], 404);
        }
        return response()->json([
            'message' => 'Data delete success',
            'status' => 'Success'
        ], 200);
    }

    public function getAll()
    {
        return CategoryTransaction::all();
    }

    public function getById($id)
    {
        return CategoryTransaction::where('id_category_transaction', $id)->first();
    }

    public function createById($id, $name)
    {
        return CategoryTransaction::create([
            'id_category_transaction' => $id,
            'name' => $name,
        ]);
    }

    public function deleteById($id)
    {
        $response = DB::table('category_transactions')->where('id_category_transaction', $id)->delete();
        return $response;
    }

    public function updateById($id, $name)
    {
        $response = DB::update("update category_transactions set name=? where id_category_transaction=?", [
            $name, $id
        ]);
        return $response;
    }

    private function generateId()
    {
        $latest = CategoryTransaction::orderBy('id_category_transaction', 'desc')->first();
        if (!$latest) {
            return 'CT0000000001';
        }
        $latestId = $latest['id_category_transaction'];
        $characterNum = 2;
        $symbol = substr($latestId, 0, $characterNum);
        $numStr = substr($latestId, $characterNum);
        $numInt = (int) $numStr;
        $numInt++;
        $zeroCount = strlen($numStr) - strlen((string)$numInt);
        $zeroStr = '';
        while ($zeroCount) {
            $zeroStr .= '0';
            $zeroCount--;
        }
        return $symbol . $zeroStr . $numInt;
    }
}
