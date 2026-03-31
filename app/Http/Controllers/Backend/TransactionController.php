<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\MobilePayTransactionDataTable;
use App\DataTables\TransactionDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(TransactionDataTable $dataTable)
    {
        return $dataTable->render('backend.transaction.index');
    }
    public function mobilePayTransaction(MobilePayTransactionDataTable $dataTable)
    {
        return $dataTable->render('backend.transaction.mobilepay_transaction');
    }
}
