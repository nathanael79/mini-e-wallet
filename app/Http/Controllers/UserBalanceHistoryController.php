<?php


namespace App\Http\Controllers;


use App\UsersBalanceHistory;

class UserBalanceHistoryController extends Controller
{
    public function getBalanceHistoryAll(){
        $data = UsersBalanceHistory::all();
        if($data->isEmpty()){
            return $this->getResponse('data is empty', $data, 404);
        }else{
            return $this->getResponse('data found', $data, 200);
        }
    }

    public function getBalanceHistoryById($id){
        $data = UsersBalanceHistory::find($id);
        if(is_null($data)){
            return $this->getResponse('data is empty', $data, 404);
        }else{
            return $this->getResponse('data found', $data, 200);
        }
    }

    public function getBalanceHistoryByUserBalanceId($userBalanceId){
        $data = UsersBalanceHistory::where('user_balance_id',$userBalanceId)->get();
        if(is_null($data)){
            return $this->getResponse('data is empty', $data, 404);
        }else{
            return $this->getResponse('data found', $data, 200);
        }
    }
}
