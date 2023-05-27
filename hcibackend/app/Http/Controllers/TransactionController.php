<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(){
        try {
            $transaction = Transaction::all();
            return response()->json(['transactions'=> $transaction], 200);
        } catch (\Exception $e) {
            return response()->json(['error'=> $e->getMessage()], 500);
        }
    }
}
