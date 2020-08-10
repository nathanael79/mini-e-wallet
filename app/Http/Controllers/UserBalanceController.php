<?php


namespace App\Http\Controllers;


use App\BlanceBank;
use App\BlanceBankHistory;
use App\UsersBalance;
use App\UsersBalanceHistory;
use Illuminate\Http\Request;
use Exception;

class UserBalanceController extends Controller
{
    public function createBalance(Request $request){
        $userBalance = UsersBalance::where('user_id', $request->user_id)->latest('balance_achieve')->first();
        if(is_null($userBalance)){
            if($request->type == 'credit'){
                return $this->getResponse('credit is not allowed, balance is 0', ' ', 405);
            }else{
                $data = UsersBalance::create(array_merge($request->only(['user_id','balance','balance_achieve']), ['balance_achieve' => 0 + $request->balance]));
                $history = UsersBalanceHistory::create([
                    'user_balance_id' => $data->id,
                    'balance_before' => 0,
                    'balance_after' => $data->balance_achieve,
                    'activity' => 'Top Up',
                    'type' => 'debit',
                    'user_agent' => $request->user_agent
                ]);

                return $this->getResponse('debit success', ['data' => $data, 'history' => $history], 200);
            }
        }else{
            if($request->type == 'credit'){
                if($request->balance > $userBalance->balance_achieve){
                    return $this->getResponse('credit is not allowed, your balance amount is not sufficient', ' ', 405);
                }else{
                    $data = UsersBalance::create(array_merge($request->only(['user_id','balance','balance_achieve']),['balance_achieve' => $userBalance->balance_achieve - $request->balance]));
                    $history = UsersBalanceHistory::create([
                        'user_balance_id' => $data->id,
                        'balance_before' => $userBalance->balance_achieve,
                        'balance_after' => $data->balance_achieve,
                        'activity' => 'Transaction',
                        'type' => 'credit',
                        'user_agent' => $request->user_agent
                    ]);

                    return $this->getResponse('debit success', ['data' => $data, 'history' => $history], 200);
                }
            }else{
                $data = UsersBalance::create(array_merge($request->only(['user_id','balance','balance_achieve']),['balance_achieve' => $userBalance->balance_achieve + $request->balance]));
                $history = UsersBalanceHistory::create([
                    'user_balance_id' => $data->id,
                    'balance_before' => $userBalance->balance_achieve,
                    'balance_after' => $data->balance_achieve,
                    'activity' => 'Top Up',
                    'type' => 'debit',
                    'user_agent' => $request->user_agent
                ]);

                return $this->getResponse('debit success', ['data' => $data, 'history' => $history], 200);
            }

        }
    }

    public function transferToBank(Request $request){
        $userBalance =UsersBalance::where('user_id',$request->user_id)->latest('balance_achieve')->first();
        $balanceBank = BlanceBank::where('code',$request->code)->latest('balance_achieve')->first();

        if(is_null($userBalance)){
            return $this->getResponse('user balance not found', ' ', 404);
        }

        if(is_null($balanceBank)){
            return $this->getResponse('balance bank not found', ' ', 404);
        }

        if($request->balance > $userBalance->balance_achieve){
            return $this->getResponse('credit is not allowed, your saldo is not sufficient', ' ', 405);
        }else{
            $balanceBankData = BlanceBank::create(array_merge($request->only(['balance','balance_achieve','code','enable']), ['balance_achieve' => $balanceBank->balance_achieve + $request->balance]));

            $balanceBankHistoryData = BlanceBankHistory::create([
                'balance_bank_id' => $balanceBank->id,
                'balance_before' => $balanceBank->balance_achieve,
                'balance_after' => $balanceBankData->balance_achieve,
                'activity' => 'Top Up from E-Wallet',
                'type' => 'debit',
                'user_agent' => ' '
            ]);

            $userBalanceData = UsersBalance::create(array_merge($request->only(['user_id','balance','balance_achieve']),['balance_achieve' => $userBalance->balance_achieve - $request->balance]));

            $userBalanceHistoryData = UsersBalanceHistory::create([
                'user_balance_id' => $userBalanceData->id,
                'balance_before' => $userBalance->balance_achieve,
                'balance_after' => $userBalanceData->balance_achieve,
                'activity' => 'Transfer to Bank',
                'type' => 'credit',
                'user_agent' => ' '
            ]);

            return $this->getResponse('Transfer success',[
                'balance_bank' => $balanceBankData,
                'balance_bank_history ' => $balanceBankHistoryData,
                'user_balance' => $userBalanceData
            ],200);
        }
    }

    public function transferFromBank(Request $request){
        $userBalance = UsersBalance::where('user_id',$request->user_id)->latest('balance_achieve')->first();
        $balanceBank = BlanceBank::where('code',$request->code)->latest('balance_achieve')->first();

        if(is_null($userBalance)){
            return $this->getResponse('user balance not found', ' ', 404);
        }

        if(is_null($balanceBank)){
            return $this->getResponse('balance bank not found', ' ', 404);
        }

        if($request->balance > $balanceBank->balance_achieve){
            return $this->getResponse('credit is not allowed, your saldo is not sufficient', ' ', 405);
        }else{
            $balanceBankData = BlanceBank::create(array_merge($request->only(['balance','balance_achieve','code','enable']), ['balance_achieve' => $balanceBank->balance_achieve - $request->balance]));

            $balanceBankHistoryData = BlanceBankHistory::create([
                'balance_bank_id' => $balanceBank->id,
                'balance_before' => $balanceBank->balance_achieve,
                'balance_after' => $balanceBankData->balance_achieve,
                'activity' => 'Transfer to E-Wallet',
                'type' => 'credit',
                'user_agent' => ' '
            ]);

            $userBalanceData = UsersBalance::create(array_merge($request->only(['user_id','balance','balance_achieve']),['balance_achieve' => $userBalance->balance_achieve + $request->balance]));

            $userBalanceHistoryData = UsersBalanceHistory::create([
                'user_balance_id' => $userBalanceData->id,
                'balance_before' => $userBalance->balance_achieve,
                'balance_after' => $userBalanceData->balance_achieve,
                'activity' => 'Top up from Bank',
                'type' => 'debit',
                'user_agent' => ' '
            ]);

            return $this->getResponse('Transfer success',[
                'balance_bank' => $balanceBankData,
                'balance_bank_history ' => $balanceBankHistoryData,
                'user_balance' => $userBalanceData
            ],200);
        }
    }

    public function getAllBalance(){
        $data = UsersBalance::all();
        if($data->isEmpty()){
            return $this->getResponse('data is empty', $data, 404);
        }else{
            return $this->getResponse('data found', $data, 200);
        }
    }

    public function getBalanceById($id){
        $data = UsersBalance::with('usersBalanceHistory')->find($id);
        if(is_null($data)){
            return $this->getResponse('data is empty', $data, 404);
        }else{
            return $this->getResponse('data found', $data, 200);
        }
    }

    public function deleteBalance($id){
        $data = UsersBalance::find($id);
        if(is_null($data)){
            return $this->getResponse('data not found', '', 404);
        }else{
            try{
                $data->delete();
                return $this->getResponse('data deleted', '', 200);
            }catch (Exception $e){
                return $this->getResponse($e->getMessage(), '', 500);
            }
        }
    }

}
