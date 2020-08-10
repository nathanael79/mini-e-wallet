<?php


namespace App\Http\Controllers;


use App\BlanceBank;
use App\BlanceBankHistory;
use Illuminate\Http\Request;
use Exception;

class BalanceBankController extends Controller
{
    public function createBalance(Request $request){
        $code = $request->code;
        $balanceBank = BlanceBank::where('code', $code)->latest('balance_achieve')->first();
        if(is_null($balanceBank)){
            if($request->type == 'credit') {
                return $this->getResponse('credit is not allowed, balance is 0', ' ', 405);
            }else {
                $data = BlanceBank::create(array_merge($request->only(['balance','balance_achieve','code','enable']), ['balance_achieve' => 0 + $request->balance]));
                $history = BlanceBankHistory::create([
                    'balance_bank_id' => $data->id,
                    'balance_before' => 0,
                    'balance_after' => $data->balance_achieve,
                    'activity' => 'Top Up',
                    'type' => 'debit',
                    'user_agent' => $request->user_agent
                ]);

                return $this->getResponse('debit success', ['data' => $data, 'history' => $history], 200);
            }
        }else {
            if($request->type == 'credit'){
                if($request->balance > $balanceBank->balance_achieve){
                    return $this->getResponse('credit is not allowed, your balance amount is not sufficient', ' ', 405);
                }else{
                    $data = BlanceBank::create(array_merge($request->only(['balance','balance_achieve','code','enable']),['balance_achieve' => $balanceBank->balance_achieve - $request->balance]));
                    $history = UsersBalanceHistory::create([
                        'balance_bank_id' => $data->id,
                        'balance_before' => $balanceBank->balance_achieve,
                        'balance_after' => $data->balance_achieve,
                        'activity' => 'Transaction',
                        'type' => 'credit',
                        'user_agent' => $request->user_agent
                    ]);
                    return $this->getResponse('debit success', ['data' => $data, 'history' => $history], 200);
                }
            }else{
                $data = BlanceBank::create(array_merge($request->only(['balance','balance_achieve','code','enable']),['balance_achieve' => $balanceBank->balance_achieve + $request->balance]));
                $history = BlanceBankHistory::create([
                    'balance_bank_id' => $data->id,
                    'balance_before' => $balanceBank->balance_achieve,
                    'balance_after' => $data->balance_achieve,
                    'activity' => 'Top Up',
                    'type' => 'debit',
                    'user_agent' => $request->user_agent
                ]);

                return $this->getResponse('debit success', ['data' => $data, 'history' => $history], 200);
            }
        }

    }

    public function getAllBalance(){
        $data = BlanceBank::with('blanceBankHistory')->get();
        if($data->isEmpty()){
            return $this->getResponse('data is empty', $data, 404);
        }else{
            return $this->getResponse('data found', $data, 200);
        }
    }

    public function getBalanceById($id){
        $data = BlanceBank::with('blanceBankHistory')->find($id);
        if(is_null($data)){
            return $this->getResponse('data is empty', $data, 404);
        }else{
            return $this->getResponse('data found', $data, 200);
        }
    }

    public function deleteBalance($id){
        $data = BlanceBank::find($id);
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
