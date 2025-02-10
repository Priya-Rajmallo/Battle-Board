<?php

namespace App\Http\Controllers;

use App\Models\BankDetails;
use App\Models\Feedback;
use App\Models\GameFeedback;
use App\Models\GST;
use App\Models\Payment;
use App\Models\PlayableMatch;
use App\Models\TDS;
use App\Models\TDSMaster;
use App\Models\Tournament;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;


class DataTableController extends Controller
{

    public function gameFeedbackDataTable(Request $request)
    {
        if ($request->input('start_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $data = GameFeedback::whereBetween('date', [$startDate, $endDate])->with('user')->get();
            return DataTables::of($data)->make(true);
        } else {

            $data = GameFeedback::with('user')->get();
            return DataTables::of($data)->make(true);
        }
    }



    public function feedbackDataTable(Request $request)
    {
        if ($request->input('start_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $data = Feedback::whereBetween('date', [$startDate, $endDate])->with('user')->get();
            return DataTables::of($data)->make(true);
        } else {

            $data = Feedback::with('user')->get();
            return DataTables::of($data)->make(true);
        }
    }


    function tdsDataTable(Request $request)
    {
        if ($request->input('start_date')) {
            $startDate = $request->input('start_date', Carbon::now()->toDateString());
            $endDate = $request->input('end_date', Carbon::now()->toDateString());

            $data = TDS::whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'desc')
                ->with('user')
                ->get();

            return DataTables::of($data)->make(true);
        } else {

            $data = TDS::orderBy('date', 'desc')
                ->with('user')
                ->get();

            return DataTables::of($data)->make(true);
        }
    }



    function gstDataTable(Request $request)
    {
        if ($request->input('start_date')) {
            $startDate = $request->input('start_date', Carbon::now()->toDateString());
            $endDate = $request->input('end_date', Carbon::now()->toDateString());

    
            $data = GST::with(['user', 'transaction'])
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->orderBy('date', 'desc')
            ->get();

          return DataTables::of($data)->make(true);

        } else {

            $data = GST::with(['user', 'transaction'])
           ->orderBy('date', 'desc')
           ->get();

         return DataTables::of($data)->make(true);

        }
    }


   



    public function paymentApprovalDataTable(Request $request)
    {
        if ($request->input('start_date')) {

            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $data = Payment::join('users', 'payments.user_id', '=', 'users.str_user_id')
                ->with('transaction')
                // ->where('payments.status', '!=', 'deleted')
                ->whereBetween('date', [$startDate, $endDate])
                ->select('payments.*', 'users.name')
                ->get();
            return DataTables::of($data)->make(true);
        } else {

            $data = Payment::join('users', 'payments.user_id', '=', 'users.str_user_id')
                ->with('transaction')
                // ->where('payments.status', '!=', 'deleted')
                ->select('payments.*', 'users.name')
                ->get();
            return DataTables::of($data)->make(true);
        }
    }

    public function matchesDataTable(Request $request)
    {
        if ($request->input('start_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $data = PlayableMatch::whereBetween('date', [$startDate, $endDate])->with(['game', 'tournament'])->get();
            return DataTables::of($data)->make(true);
        } else {
            $data = PlayableMatch::with(['game', 'tournament'])->get();
            return DataTables::of($data)->make(true);
        }
    }
    public function tournamentsDataTable(Request $request)
    {
        if ($request->input('start_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $data = Tournament::whereBetween('date', [$startDate, $endDate])
                ->with('game')->get();
            return DataTables::of($data)->make(true);
        } else {

            $data = Tournament::with('game')->get();
            return DataTables::of($data)->make(true);
        }
    }

    function GSTTransactionDataTable($userId)
    {
        $data = GST::where('g_s_t_s.user_id', $userId)
            ->join('users', 'g_s_t_s.user_id', '=', 'users.str_user_id')
            ->select('g_s_t_s.*', 'users.str_user_id')
            ->with('transaction')
            ->get();

        // // Convert each column value to string
        // $data = $data->map(function ($item) {
        //     foreach ($item->getAttributes() as $key => $value) {
        //         $item->$key = (string) $value;
        //     }
        //     return $item;
        // });

        return DataTables::of($data)->make(true);
    }

    function TDSTransactionDataTable($userId)
    {
        $data = TDSMaster::where('t_d_s_masters.user_id', $userId)
            ->join('users', 't_d_s_masters.user_id', '=', 'users.str_user_id')
            ->select('t_d_s_masters.*', 'users.str_user_id')
            ->with('transaction')
            ->get();

        // // Convert each column value to string
        // $data = $data->map(function ($item) {
        //     foreach ($item->getAttributes() as $key => $value) {
        //         $item->$key = (string) $value;
        //     }
        //     return $item;
        // });

        return DataTables::of($data)->make(true);
    }

    function transactionDataTable($userId)
    {
        $data = Transaction::where('transactions.user_id', $userId)
            ->join('users', 'transactions.user_id', '=', 'users.str_user_id')
            ->select('transactions.*', 'users.str_user_id')
            ->get();

        // // Convert each column value to string
        // $data = $data->map(function ($item) {
        //     foreach ($item->getAttributes() as $key => $value) {
        //         $item->$key = (string) $value;
        //     }
        //     return $item;
        // });

        return DataTables::of($data)->make(true);
    }

    function bankDetailsDataTable(Request $request)
    {
        if ($request->input('start_date')) {
            $startDate = $request->input('start_date', Carbon::now()->toDateString()) . ' 00:00:00';
            $endDate = $request->input('end_date', Carbon::now()->toDateString()) . ' 23:59:59';

            $data = BankDetails::leftJoin('users', 'bank_details.user_id', '=', 'users.str_user_id')
                ->whereBetween('users.created_at', [$startDate, $endDate])
                ->select(
                    'bank_details.user_id',
                    'bank_details.bank_name',
                    'bank_details.account_name',
                    'bank_details.account_number',
                    'bank_details.ifsc_code',
                    'bank_details.status',
                    'users.name',
                    'users.user_type',
                    'bank_details.created_at',
                    'bank_details.updated_at',
                )->get();

            return DataTables::of($data)->make(true);
        } else {

            $data = BankDetails::leftJoin('users', 'bank_details.user_id', '=', 'users.str_user_id')
                ->select(
                    'bank_details.user_id',
                    'bank_details.bank_name',
                    'bank_details.account_name',
                    'bank_details.account_number',
                    'bank_details.ifsc_code',
                    'bank_details.status',
                    'users.name',
                    'users.user_type',
                    'bank_details.created_at',
                    'bank_details.updated_at',
                )->get();

            return DataTables::of($data)->make(true);
        }
    }

    function withdrawalsApprovalDataTable(Request $request)
    {

        if ($request->input('start_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $data = WithdrawalRequest::join('users', 'withdrawal_requests.user_id', '=', 'users.str_user_id')
                ->whereBetween('date', [$startDate, $endDate])
                ->select('withdrawal_requests.*', 'users.name')
                ->get();

            return DataTables::of($data)->make(true);
        } else {
            $data = WithdrawalRequest::join('users', 'withdrawal_requests.user_id', '=', 'users.str_user_id')
                ->select('withdrawal_requests.*', 'users.name')
                ->get();

            return DataTables::of($data)->make(true);
        }
    }


    function promotersApprovalDataTable(Request $request)
    {
        if ($request->input('start_date')) {
            $startDate = $request->input('start_date', Carbon::now()->toDateString()) . ' 00:00:00';
            $endDate = $request->input('end_date', Carbon::now()->toDateString()) . ' 23:59:59';

            $data = User::where('users.user_type', 'user')->where('users.apply_for_promoter', 2)
                ->whereBetween('users.created_at', [$startDate, $endDate])
                ->get();

            return DataTables::of($data)->make(true);
        } else {

            $data = User::where('users.user_type', 'user')->where('users.apply_for_promoter', 2)
                ->get();

            return DataTables::of($data)->make(true);
        }
    }

    function promotersDataTable(Request $request)
    {

        if ($request->input('start_date')) {
            $startDate = $request->input('start_date', Carbon::now()->toDateString()) . ' 00:00:00';
            $endDate = $request->input('end_date', Carbon::now()->toDateString()) . ' 23:59:59';


            $data = User::where('users.user_type', 'promoter')
                ->whereBetween('users.created_at', [$startDate, $endDate])
                ->leftJoin('bank_details', 'bank_details.user_id', '=', 'users.str_user_id')
                ->select(
                    'bank_details.user_id',
                    'bank_details.bank_name',
                    'bank_details.account_name',
                    'bank_details.account_number',
                    'bank_details.ifsc_code',
                    'bank_details.status as bank_status',
                    'users.*',
                )
                ->get();
            return DataTables::of($data)->make(true);
        } else {

            $data = User::where('users.user_type', 'promoter')
                ->leftJoin('bank_details', 'bank_details.user_id', '=', 'users.str_user_id')
                ->select(
                    'bank_details.user_id',
                    'bank_details.bank_name',
                    'bank_details.account_name',
                    'bank_details.account_number',
                    'bank_details.ifsc_code',
                    'bank_details.status as bank_status',
                    'users.*',
                )
                ->get();
            return DataTables::of($data)->make(true);
        }
    }

    function adminsDataTable(Request $request)
    {
        if ($request->input('start_date')) {
            $startDate = $request->input('start_date', Carbon::now()->toDateString()) . ' 00:00:00';
            $endDate = $request->input('end_date', Carbon::now()->toDateString()) . ' 23:59:59';

            $data = User::where('users.user_type', 'admin')
                ->whereBetween('users.created_at', [$startDate, $endDate])
                ->leftJoin('bank_details', 'bank_details.user_id', '=', 'users.str_user_id')
                ->select(
                    'bank_details.user_id',
                    'bank_details.bank_name',
                    'bank_details.account_name',
                    'bank_details.account_number',
                    'bank_details.ifsc_code',
                    'bank_details.status as bank_status',
                    'users.*',
                )
                ->get();
            return DataTables::of($data)->make(true);
        } else {
            $data = User::where('users.user_type', 'admin')
                ->leftJoin('bank_details', 'bank_details.user_id', '=', 'users.str_user_id')
                ->select(
                    'bank_details.user_id',
                    'bank_details.bank_name',
                    'bank_details.account_name',
                    'bank_details.account_number',
                    'bank_details.ifsc_code',
                    'bank_details.status as bank_status',
                    'users.*',
                )
                ->get();
            return DataTables::of($data)->make(true);
        }
    }

    function usersDataTable(Request $request)
    {
        if ($request->input('start_date')) {

            $startDate = $request->input('start_date', Carbon::now()->toDateString()) . ' 00:00:00';
            $endDate = $request->input('end_date', Carbon::now()->toDateString()) . ' 23:59:59';

            $data = User::where('users.user_type', 'user')
                ->whereBetween('users.created_at', [$startDate, $endDate])
                ->leftJoin('bank_details', 'bank_details.user_id', '=', 'users.str_user_id')
                ->select(
                    'bank_details.user_id',
                    'bank_details.bank_name',
                    'bank_details.account_name',
                    'bank_details.account_number',
                    'bank_details.ifsc_code',
                    'bank_details.status as bank_status',
                    'users.*',
                )
                ->get();

            return DataTables::of($data)->make(true);
        } else {
            $data = User::where('users.user_type', 'user')
                ->leftJoin('bank_details', 'bank_details.user_id', '=', 'users.str_user_id')
                ->select(
                    'bank_details.user_id',
                    'bank_details.bank_name',
                    'bank_details.account_name',
                    'bank_details.account_number',
                    'bank_details.ifsc_code',
                    'bank_details.status as bank_status',
                    'users.*',
                )
                ->get();

            return DataTables::of($data)->make(true);
        }
    }
}
