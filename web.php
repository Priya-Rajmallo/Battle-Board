<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CoinConversionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataTableController;
use App\Http\Controllers\DepositRequestController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\GSTController;
use App\Http\Controllers\LeaderBoardController;
use App\Http\Controllers\MinBetValueController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlayableMatchController;
use App\Http\Controllers\PromoterController;
use App\Http\Controllers\PromoterPercentageController;
use App\Http\Controllers\PromoterStatusController;
use App\Http\Controllers\PushNotificationController;
use App\Http\Controllers\TDSController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WithdrawalRequestController;
use App\Models\CoinConversion;
use App\Models\MinBetValue;
use App\Models\PromoterPercentage;
use App\Models\PromoterStatus;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('auth.login');
// })->name('index2');

Route::group(['middleware' => 'guest'], function () {
    // Route::get('/', function () {
    //     return view('welcome');
    // });

    Route::get('/', function () {
        return view('auth.login');
    })->name('index2');

    Route::get('/index', function () {
        return view('admin.index');
    })->name('index');

    // Route::get('/', function () {
    //     return view('admin.index');
    // })->name('index2');

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    // Route::redirect('/', '/api/', 301);
    // Route::get('/login', [AuthController::class, 'LoginNotFound'])->name('login');

    Route::post('/admin-login', [AuthController::class, 'adminLoginPost'])->name('admin.Login.Post');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [DashboardController::class, 'adminDashboard'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.Dashboard');

    Route::get('/users', function () {
        return view('admin.users');
    })->name('users');

    Route::get('/admins', function () {
        return view('admin.admins');
    })->name('admins');

    Route::get('/promoters', function () {
        return view('admin.promoters');
    })->name('promoters');


    Route::get('/promoter-approval', function () {
        return view('admin.promoter_approval');
    })->name('promotersApproval');


    Route::get('/tds', function () {
        $users = User::where(function ($query) {
            $query->where('user_type', 'user')
                ->orWhere('user_type', 'promoter');
        })->where('status', '1')->orderBy('name', 'asc')->get();

        return view('admin.tds', compact('users'));
    })->name('TDS');


    Route::get('/gst', function () {
        $users = User::where(function ($query) {
            $query->where('user_type', 'user')
                ->orWhere('user_type', 'promoter');
        })->where('status', '1')->orderBy('name', 'asc')->get();

        return view('admin.gst', compact('users'));
    })->name('GST');


    Route::get('/withdrawal', function () {
        $users = User::where(function ($query) {
            $query->where('user_type', 'user')
                ->orWhere('user_type', 'promoter');
        })->where('status', '1')->orderBy('name', 'asc')->get();

        return view('admin.withdrawal', compact('users'));
    })->name('Withdrawal');


    Route::get('/payment-approval', function () {
        return view('admin.payment_approval');
    })->name('paymentsApproval');


    Route::get('/withdrawal-approval', function () {
        $users = User::orderBy('name', 'asc')->with('wallet')->get();
        return view('admin.withdrawalApproval', compact('users'));
    })->name('withdrawalsApproval');

    Route::get('/bank-details', function () {
        return view('admin.bankDetails');
    })->name('bankDetails');


    Route::get('/promoter-status', function () {
        $promoter = PromoterStatus::first();
        return view('admin.promoter_status', compact('promoter'));
    })->name('promoter.status');


    Route::get('/coin-conversion', function () {
        $coin = CoinConversion::first();
        return view('admin.coin-conversion', compact('coin'));
    })->name('coin.conversion');

    Route::get('/promoter-percentage', function () {
        $percentage = PromoterPercentage::all();
        return view('admin.promoter-percentage', compact('percentage'));
    })->name('promoter.percentage');

    Route::get('/min-bet-value', function () {
        $bet = MinBetValue::first();
        return view('admin.min-bet-value', compact('bet'));
    })->name('min.bet.value');


    Route::get('/tournaments', function () {
        return view('admin.tournaments');
    })->name('admin.tournaments');



    Route::get('/matches', function () {
        return view('admin.matches');
    })->name('admin.matches');


    Route::get('/feedback', function () {
        return view('admin.feedback');
    })->name('admin.feedback');

    Route::get('/feedback-dataTable', [DataTableController::class, 'feedbackDataTable'])->name('feedback.DataTable');
    Route::get('/feedback-view/{feedbackId}', [FeedbackController::class, 'viewFeedback'])->name('admin.feedback.view');




    Route::get('/game-feedback', function () {
        return view('admin.game-feedback');
    })->name('admin.game.feedback');

    Route::get('/game-feedback-dataTable', [DataTableController::class, 'gameFeedbackDataTable'])->name('game.feedback.DataTable');
    Route::get('/game-feedback-view/{feedbackId}', [FeedbackController::class, 'viewGameFeedback'])->name('admin.game.feedback.view');




    // Route::get('/profile', function () {
    //     return view('admin.profile');
    // })->name('profile');
    Route::get('/users-dataTable', [DataTableController::class, 'usersDataTable'])->name('users.DataTable');
    Route::get('/admins-dataTable', [DataTableController::class, 'adminsDataTable'])->name('admins.DataTable');
    Route::get('/promoters-dataTable', [DataTableController::class, 'promotersDataTable'])->name('promoters.DataTable');
    Route::get('/tds-dataTable', [DataTableController::class, 'tdsDataTable'])->name('tds.DataTable');
    Route::get('/gst-dataTable', [DataTableController::class, 'gstDataTable'])->name('gst.DataTable');
    Route::get('/withdrawal-dataTable', [DataTableController::class, 'withdrawalDataTable'])->name('withdrawal.DataTable');
    Route::get('/promotersApproval-dataTable', [DataTableController::class, 'promotersApprovalDataTable'])->name('promotersApproval.DataTable');
    Route::get('/paymentApproval-dataTable', [DataTableController::class, 'paymentApprovalDataTable'])->name('paymentApproval.DataTable');
    Route::get('/withdrawalsApproval-dataTable', [DataTableController::class, 'withdrawalsApprovalDataTable'])->name('withdrawalsApproval.DataTable');
    Route::put('/promoter-Approval-Action', [PromoterController::class, 'promoterApprovalAction'])->name('promoterApprovalAction');
    Route::put('/payment-Approval-Action', [PaymentController::class, 'paymentApprovalAction'])->name('paymentApprovalAction');
    Route::put('/payment-delete', [PaymentController::class, 'paymentDelete'])->name('paymentDelete');
    Route::put('/withdrawal-Approval-Action', [WithdrawalRequestController::class, 'withdrawalApprovalAction'])->name('withdrawalApprovalAction');


    Route::get('/bankDetails-dataTable', [DataTableController::class, 'bankDetailsDataTable'])->name('bankDetails.DataTable');

    Route::get('/gst-logs/{userId}', [GSTController::class, 'viewGSTLogs'])->name('admin.gst.logs');
    Route::get('/gst-invoice/{transactionId}', [GSTController::class, 'viewInvoice'])->name('gst.invoice');

    Route::get('/tds-logs/{userId}', [TDSController::class, 'viewTDSLogs'])->name('admin.tds.logs');
    Route::get('/tds-invoice/{transactionId}', [TDSController::class, 'viewInvoice'])->name('tds.invoice');

    Route::get('/profile/{userId}', [UserController::class, 'viewProfile'])->name('profile');

    Route::get('/profile/edit/{userId}', [UserController::class, 'editProfile'])->name('editProfile');

    Route::get('/transaction-dataTable/{userId}', [DataTableController::class, 'transactionDataTable'])->name('transactions.DataTable');
    Route::get('/gst-transaction-dataTable/{userId}', [DataTableController::class, 'GSTTransactionDataTable'])->name('gst.transactions.DataTable');
    Route::get('/tds-transaction-dataTable/{userId}', [DataTableController::class, 'TDSTransactionDataTable'])->name('tds.transactions.DataTable');

    Route::post('/promoter-status/toggle', [PromoterStatusController::class, 'toggleStatus'])->name('promoter_status.toggle');
    Route::post('/promoter-downgrade', [PromoterController::class, 'promoterDowngrade'])->name('promoter.downgrade');
    Route::post('/promoter-upgrade', [PromoterController::class, 'promoterUpgrade'])->name('promoter.upgrade');

    Route::post('/wallet-deposit', [DepositRequestController::class, 'walletDepositAdmin'])->name('admin.wallet-deposit');
    Route::post('/wallet-withdrawal', [WithdrawalRequestController::class, 'walletWithdrawalAdmin'])->name('admin.wallet-withdrawal');


    Route::get('/tournaments-dataTable', [DataTableController::class, 'tournamentsDataTable'])->name('tournaments.DataTable');
    Route::get('/matches-dataTable', [DataTableController::class, 'matchesDataTable'])->name('matches.DataTable');


    Route::get('/tournament-view/{tournamentId}', [TournamentController::class, 'tournamentView'])->name('tournament.view');
    Route::get('/match-view/{matchId}', [PlayableMatchController::class, 'matchView'])->name('match.view');


    Route::post('/tds-store', [TDSController::class, 'tdsStore'])->name('admin.tds.store');
    Route::post('/coin-conversion', [CoinConversionController::class, 'store'])->name('coin.conversion');
    Route::post('/promoter-percentage', [PromoterPercentageController::class, 'store'])->name('promoter.percentage');
    Route::post('/min-bet-value', [MinBetValueController::class, 'store'])->name('min.bet.value');


    Route::post('/delete-transaction', [TransactionController::class, 'deleteTransaction'])->name('admin.delete.transaction');

    Route::get('/leader-board', [LeaderBoardController::class, 'leaderBoardIndex'])->name('admin.leader.board.index');
    Route::get('/global-leader-board', [LeaderBoardController::class, 'globalLeaderBoardIndex'])->name('admin.global.leader.board.index');

    Route::get('/player-notification', [NotificationController::class, 'playerNotification'])->name('admin.player.notification');
    Route::post('/notification-send', [NotificationController::class, 'notificationSend'])->name('admin.notification.send');
    Route::get('/notification-create', [NotificationController::class, 'notificationCreate'])->name('admin.notification.create');
    Route::get('/notification-delete/{notificationId}', [NotificationController::class, 'notificationDelete'])->name('admin.notification.delete');

    Route::get('/push-notification', [PushNotificationController::class, 'pushNotification'])->name('admin.push.notification');
    Route::post('/push-notification', [PushNotificationController::class, 'sendNotificationWeb'])->name('send.push.notification');

    Route::get('/admin-logout', [AuthController::class, 'adminLogout'])->name('admin.Logout');
});

// Route::get('/testCr', [PushNotificationController::class, 'testCronjobs']);
// Route::get('/cr', [PushNotificationController::class, 'sendNotificationCronjobs']);
