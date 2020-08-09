<?php


namespace App\Http\Controllers;


use App\UsersBalance;
use Illuminate\Http\Request;
use Exception;

class UserBalanceController extends Controller
{
    public function createBalance(Request $request){
        try{
            $userBalance = UsersBalance::where('user_id', $request->user_id)->latest('balance_achieve')->first();
            if(is_null($userBalance)){
                $data = UsersBalance::create(array_merge($request->all(),['balance_achieve' => 0 + $request->balance]));
            }else{
                $data = $userBalance->create(array_merge($request->all(),['balance_achieve' => $userBalance->balance_achieve + $request->balance]));
            }
            return $this->getResponse('data created', $data, 201);
        }catch (Exception $e){
            return $this->getResponse($e->getMessage(), '', 500);
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
        $data = UsersBalance::find($id);
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

    private function getResponse($message = '', $data = '', $code = 200){
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }

}
