<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('transactions')
            ->join('category_transactions', 'transactions.id_category_transaction', '=', 'category_transactions.id_category_transaction')
            ->join('payment_methods', 'transactions.id_payment_method', '=', 'payment_methods.id_payment_method')
            ->select(
                'transactions.*',
                'category_transactions.name as category_transactions_name',
                'payment_methods.name as payment_methods_name'
            )
            ->get();
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
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['numeric', 'required'],
            'date' => ['date'],
            'is_income' => 'boolean',
            'id_category_transaction' => ['required', 'string'],
            'id_payment_method' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'message' => 'Validation Error',
                'status' => 'Error'
            ], 400);
        }
        $id_category_transaction = $request->id_category_transaction;
        $id_payment_method = $request->id_payment_method;
        if ($id_category_transaction && !(new CategoryTransactionController)->getById($id_category_transaction)) {
            return response()->json([
                'message' => 'id_category_transaction not found',
                'status' => 'Error',
            ], 404);
        }
        if ($id_payment_method && !(new PaymentMethodController)->getById($id_payment_method)) {
            return response()->json([
                'message' => 'id_payment_method not found',
                'status' => 'Error',
            ], 404);
        }

        $response = Transaction::create($request->all());
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
        $data = DB::table('transactions')
            ->join('category_transactions', 'transactions.id_category_transaction', '=', 'category_transactions.id_category_transaction')
            ->join('payment_methods', 'transactions.id_payment_method', '=', 'payment_methods.id_payment_method')
            ->select(
                'transactions.*',
                'category_transactions.name as category_transactions_name',
                'payment_methods.name as payment_methods_name'
            )
            ->where('id', $id)
            ->first();
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
            'id' => 'required',
            'name' => ['string', 'max:255'],
            'amount' => ['numeric'],
            'date' => ['date'],
            'is_income' => 'boolean',
            'id_category_transaction' => 'string',
            'id_payment_method' => 'string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'message' => 'Validation Error',
                'status' => 'Error'
            ], 400);
        }

        $target = $this->getById($request->id);
        if (!$target) {
            return response()->json([
                'message' => 'Data not found',
                'status' => 'Error',
            ], 404);
        }

        $id_category_transaction = $request->id_category_transaction;
        $id_payment_method = $request->id_payment_method;
        if ($id_category_transaction && !(new CategoryTransactionController)->getById($id_category_transaction)) {
            return response()->json([
                'message' => 'id_category_transaction not found',
                'status' => 'Error',
            ], 404);
        }
        if ($id_payment_method && !(new PaymentMethodController)->getById($id_payment_method)) {
            return response()->json([
                'message' => 'id_payment_method not found',
                'status' => 'Error',
            ], 404);
        }

        $response = $target->update($request->all());
        if (!$response) {
            return response()->json([
                'message' => 'Database error',
                'status' => 'Error',
            ], 500);
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
    public function destroy($id)
    {
        $data = Transaction::find($id);
        if (!$data) {
            return response()->json([
                'message' => 'Data not found',
                'status' => 'Error',
            ], 404);
        }
        $response = $data->delete();
        if (!$response) {
            return response()->json([
                'message' => 'Database error',
                'status' => 'Error',
            ], 500);
        }
        return response()->json([
            'message' => 'Data deleted success',
            'status' => 'Success',
        ], 200);
    }

    public function getById($id)
    {
        return Transaction::find($id);
    }
}
