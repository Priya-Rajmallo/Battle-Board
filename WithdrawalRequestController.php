<?php

namespace App\Http\Controllers;

use App\Models\CoinConversion;
use App\Models\TDSMaster;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WithdrawalLog;
use App\Models\WithdrawalRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class WithdrawalRequestController extends Controller
{
    public function walletWithdrawalAdmin(Request $request)
    {
        $rules = [
            'userId' => 'required|exists:users,str_user_id',
            'walletSection' => 'required|in:Bonus,Deposit,Earn',
            'withdrawalDate' => 'required|date',
            'coin' => 'required|numeric',
            'transaction_method' => 'required|max:255',
            'transaction_ref' => 'nullable|max:255',
            'GenerateTdsInvoice' => 'required|in:yes,no',
            'TdsDeduction' => 'required|in:yes,no',
        ];

        $messages = [];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                'status' => false,
                'message' => $error
            ], 400);
        }

        try {
            DB::beginTransaction(); // Start the database action

            $ccValue = CoinConversion::pluck('coin_value')->first();
            // $convertCoin = $request->amount * $ccValue;
            $convertAmount = $request->coin / $ccValue;
            // $convertCoin = $request->amount * 100;


            if ($request->GenerateTdsInvoice === 'yes') {

                // // Get the input coin
                // $coin = $request->input('coin');

                if ($request->TdsDeduction === 'yes') {

                // Calculate 30% TDS
                $tdsPercentage = 30;
                $tdsAmount = ($convertAmount * $tdsPercentage) / 100;

                // Calculate the remaining convertAmount after deducting TDS
                $remainingAmount = $convertAmount - $tdsAmount;
                }else{
                    $remainingAmount = $convertAmount;
                }
            } else {
                if ($request->TdsDeduction === 'yes') {

                    // Calculate 30% TDS
                    $tdsPercentage = 30;
                    $tdsAmount = ($convertAmount * $tdsPercentage) / 100;
    
                    // Calculate the remaining convertAmount after deducting TDS
                    $remainingAmount = $convertAmount - $tdsAmount;
                    }else{
                        $remainingAmount = $convertAmount;
                    }
            }

            $currentDateTime = Carbon::now()->format('YmdHis');

            $transaction = new Transaction();
            $transaction->user_id = $request->userId;
            $transaction->transaction_id = $currentDateTime;
            $transaction->transaction_date = $request->withdrawalDate;
            // $transaction->transaction_coin = $convertCoin;
            $transaction->transaction_coin = $request->coin;
            $transaction->transaction_type = 'debit';
            $transaction->transaction_details = 'Wallet Withdrawal from admin login.';
            $transaction->transaction_amount = $remainingAmount;
            // $transaction->transaction_amount = $convertAmount;
            $transaction->transaction_method = $request->transaction_method;
            $transaction->transaction_ref = $request->transaction_ref;
            $transaction->transaction_status = 1;
            $transaction->save();

            $wallet = Wallet::where('user_id', $request->userId)->first();

            if ($request->walletSection == "Bonus") {
                if ($request->coin > $wallet->bonus) {
                    return response()->json([
                        'status' => false,
                        'message' => "Insufficient balance. You have " . $wallet->bonus . " bonus coins.",
                    ], 400);
                }
                $wallet->bonus -= $request->coin;
            } elseif ($request->walletSection == "Deposit") {
                if ($request->coin > $wallet->deposit) {
                    return response()->json([
                        'status' => false,
                        'message' => "Insufficient balance. You have " . $wallet->deposit . " deposit coins.",
                    ], 400);
                }
                $wallet->deposit -= $request->coin;
            } elseif ($request->walletSection == "Earn") {
                if ($request->coin > $wallet->earn) {
                    return response()->json([
                        'status' => false,
                        'message' => "Insufficient balance. You have " . $wallet->earn . " earn coins.",
                    ], 400);
                }
                $wallet->earn -= $request->coin;
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Something wrong in wallet section.'
                ], 403);
            }

            if ($request->GenerateTdsInvoice === 'yes') {
                if ($request->TdsDeduction === 'yes') {
                $tds = new TDSMaster();
                $tds->transaction_id = $transaction->id;
                $tds->user_id = $request->userId;
                $tds->date = $request->withdrawalDate;
                $tds->percentage = $tdsPercentage;
                $tds->total_amount = $convertAmount;
                $tds->tds_amount = $tdsAmount;
                $tds->remaining_amount = $remainingAmount;
                $tds->save();
            }
            }


            $withdrawalReq = new WithdrawalRequest();
            $withdrawalReq->is_admin_withdrawal = 1;
            $withdrawalReq->user_id = $request->userId;
            $withdrawalReq->wallet_section = $request->walletSection;
            $withdrawalReq->date = $request->withdrawalDate;
            $withdrawalReq->inr = $remainingAmount;
            $withdrawalReq->coin = $request->coin;
            $withdrawalReq->transaction_method = $request->transaction_method;
            $withdrawalReq->transaction_ref = $request->transaction_ref;
            $withdrawalReq->transaction_id = $transaction->id;
            $withdrawalReq->status = 1; // approved
            $withdrawalReq->save();


            $log = new WithdrawalLog();
            $log->date = Carbon::now();
            $log->wallet_section = $request->walletSection;
            $log->action_date = $log->date;
            $log->current_user = $request->userId;
            $log->type = "true";
            $log->withdrawalreq_id =  $withdrawalReq->id;

            $log->user_id = $request->userId;
            $log->withdrawal_date = $request->withdrawalDate;
            $log->inr = $remainingAmount;
            $log->coin =  $request->coin;
            $log->withdrawal_status = 1; // Approved
            $log->save();


            $wallet->withdrawal += $request->coin;
            $wallet->save();


            // If everything is successful, commit the action
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Withdrawal successfully.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => "Something went wrong.",
                'exception' => $e->getMessage(),
            ], 403);
        }
    }

    public function withdrawalApprovalAction(Request $request)
    {
        $rules = [
            'withdrawal_id' => 'required|exists:withdrawal_requests,id',
            'approval_value' => 'required|integer',
        ];

        $messages = [
            // 'user_id.required' => 'The user id field is required.',
            // 'user_id.integer' => 'The user id must be a integer.',

            'approval_value.required' => 'The approval field is required.',
            'approval_value.integer' => 'The approval must be a integer.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                'status' => false,
                'message' => $error
            ], 400);
        }

        try {
            DB::beginTransaction(); // Start the database action

            $withdrawalRequest = WithdrawalRequest::where('id', $request->withdrawal_id)->firstOrFail();

            $user = User::where('str_user_id', $withdrawalRequest->user_id)->first();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found.',
                ], 404);
            }

            $wallet = Wallet::where('user_id', $withdrawalRequest->user_id)->first();

            if ($withdrawalRequest->wallet_section == "Bonus") {
                if ($withdrawalRequest->coin > $wallet->bonus) {
                    return response()->json([
                        'status' => false,
                        'message' => "Insufficient balance. You have " . $wallet->bonus . " bonus coins.",
                        'availableBalance' => $wallet->bonus,
                    ], 400);
                }

                $wallet->bonus -= $withdrawalRequest->coin;
            } elseif ($withdrawalRequest->wallet_section == "Deposit") {
                if ($withdrawalRequest->coin > $wallet->deposit) {
                    return response()->json([
                        'status' => false,
                        'message' => "Insufficient balance. You have " . $wallet->deposit . " deposit coins.",
                        'availableBalance' => $wallet->deposit,
                    ], 400);
                }

                $wallet->deposit -= $withdrawalRequest->coin;
            } elseif ($withdrawalRequest->wallet_section == "Earn") {
                if ($withdrawalRequest->coin > $wallet->earn) {
                    return response()->json([
                        'status' => false,
                        'message' => "Insufficient balance. You have " . $wallet->earn . " earn coins.",
                        'availableBalance' => $wallet->earn,
                    ], 400);
                }

                $wallet->earn -= $withdrawalRequest->coin;
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Something wrong in wallet section.'
                ], 403);
            }



            if ($request->approval_value == 1) {
                $jsonMessage = "Request approved successfully.";

                $currentDateTime = Carbon::now()->format('YmdHis');
                $transaction = new Transaction();
                $transaction->user_id = $withdrawalRequest->user_id;
                $transaction->transaction_id = $currentDateTime;
                $transaction->transaction_date = Carbon::now();
                $transaction->transaction_type = 'debit';
                $transaction->transaction_details = 'Wallet withdrawal.';
                $transaction->transaction_coin = $withdrawalRequest->coin;
                $transaction->transaction_amount = $withdrawalRequest->inr;
                $transaction->transaction_method = null;
                $transaction->transaction_ref = $withdrawalRequest->id;
                $transaction->transaction_status = 1;
                $transaction->save();

                $withdrawalRequest->transaction_method = $transaction->transaction_method;
                $withdrawalRequest->transaction_ref = $transaction->id;
                $withdrawalRequest->transaction_id = $transaction->id;
                $withdrawalRequest->status = $request->approval_value;
                $withdrawalRequest->save();


                $log = WithdrawalLog::where('withdrawalreq_id', $request->withdrawal_id)->first();
                $log->action_date = Carbon::now();
                $log->type = "true";

                $log->transaction_method = $transaction->transaction_method;
                $log->transaction_ref = $transaction->id;
                $log->transaction_id = $transaction->id;
                $log->withdrawal_status = $request->approval_value;
                $log->save();



                // if ($withdrawalRequest->coin >= $earnBal) {
                //     $wallet->earn -= $earnBal;
                //     $remainingBal = $withdrawalRequest->coin - $earnBal;
                //     $wallet->deposit -= $remainingBal;
                // } else {
                //     $wallet->earn -= $withdrawalRequest->coin;
                // }

                $wallet->withdrawal += $withdrawalRequest->coin;
                $wallet->save();
            } else {
                $jsonMessage = "Request rejected successfully.";
                $withdrawalRequest->status = $request->approval_value;
                $withdrawalRequest->save();

                $log = WithdrawalLog::where('withdrawalreq_id', $request->withdrawal_id)->first();
                $log->action_date = Carbon::now();
                $log->type = "true";

                $log->withdrawal_status = $request->approval_value;
                $log->save();
            }

            // If everything is successful, commit the action
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => $jsonMessage
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => "Something went wrong.",
                'exception' => $e->getMessage(),
            ], 403);
        }
    }

    public function walletWithdraw(Request $request)
    {
        $rules = [
            'withdrawalAmount' => 'required|numeric',
            'walletSection' => 'required|in:Bonus,Deposit,Earn',
            'transaction_method' => 'required|max:255',
            'transaction_ref' => 'nullable',
        ];

        $messages = [
            'withdrawalAmount.required' => 'The withdrawal amount is required.',
            'withdrawalAmount.numeric' => 'The withdrawal amount must be a number.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                'status' => false,
                'message' => $error
            ], 400);
        }

        try {
            DB::beginTransaction(); // Start the database action

            $user = User::where('str_user_id', $request->loginUser->str_user_id)->first();

            if (!$user->wallet_auth || !$user->wallet_auth == 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'You are unauthorized to do this.'
                ], 401);
            }


            $ccValue = CoinConversion::pluck('coin_value')->first();
            $convertInr = $request->withdrawalAmount / $ccValue;

            // $convertInr = $request->withdrawalAmount / 100;

            $wallet = Wallet::where('user_id', $request->loginUser->str_user_id)->first();

            if ($request->walletSection == "Bonus") {
                if ($request->withdrawalAmount > $wallet->bonus) {
                    return response()->json([
                        'status' => false,
                        'message' => "Insufficient balance. You have " . $wallet->bonus . " bonus coins.",
                    ], 400);
                }
            } elseif ($request->walletSection == "Deposit") {
                if ($request->withdrawalAmount > $wallet->deposit) {
                    return response()->json([
                        'status' => false,
                        'message' => "Insufficient balance. You have " . $wallet->deposit . " deposit coins.",
                    ], 400);
                }
            } elseif ($request->walletSection == "Earn") {
                if ($request->withdrawalAmount > $wallet->earn) {
                    return response()->json([
                        'status' => false,
                        'message' => "Insufficient balance. You have " . $wallet->earn . " earn coins.",
                    ], 400);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Something wrong in wallet section.'
                ], 403);
            }

            $withdrawalReq = new WithdrawalRequest();
            $withdrawalReq->user_id = $request->loginUser->str_user_id;
            $withdrawalReq->wallet_section = $request->walletSection;
            $withdrawalReq->date = Carbon::today();
            $withdrawalReq->inr = $convertInr;
            $withdrawalReq->coin = $request->withdrawalAmount;
            $withdrawalReq->transaction_method = $request->transaction_method;
            $withdrawalReq->transaction_ref = $request->transaction_ref;
            $withdrawalReq->status = 2; // Pending
            $withdrawalReq->save();


            $log = new WithdrawalLog();
            $log->date = Carbon::now();
            $log->wallet_section = $request->walletSection;
            $log->current_user = $request->loginUser->str_user_id;
            $log->type = "false";
            $log->withdrawalreq_id =  $withdrawalReq->id;

            $log->user_id = $request->loginUser->str_user_id;
            $log->withdrawal_date = Carbon::today();
            $log->inr = $convertInr;
            $log->coin = $request->withdrawalAmount;
            $log->withdrawal_status = 2; // Pending
            $log->save();


            $user->wallet_auth = 0;
            $user->save();


            // If everything is successful, commit the action
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Withdrawal request submitted.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => "Something went wrong.",
                'exception' => $e->getMessage(),
            ], 403);
        }
    }
}
