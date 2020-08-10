<?php


namespace App\Http\Controllers;


use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function createUser(Request $request){
        $this->validate($request,[
            'username' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6'
        ]);

        try{
            $data = User::create(array_merge($request->all(),['password' => Hash::make($request->password)]));
            return $this->getResponse('data created', $data, 201);
        }catch (Exception $e){
            return $this->getResponse($e->getMessage(), '', 500);
        }
    }

    public function updateUser($id, Request $request){
        $user = User::find($id);
        if(is_null($user)){
            return $this->getResponse('user not found', '', 404);
        }else{
            $this->validate($request,[
                'username' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required|min:6'
            ]);

            try{
                $user->update($request->all());
                return $this->getResponse('user updated', $user , 200);
            }catch (Exception $e){
                return $this->getResponse($e->getMessage(), '', 500);
            }
        }
    }

    public function getUsers(){
        $data = User::all();

        if($data->isEmpty()){
            return $this->getResponse('data is empty', '', 404);
        }else{
            return $this->getResponse('data found', $data, 200);
        }
    }

    public function getUserById($id){
        $data = User::find($id);

        if($data == null){
            return $this->getResponse('data not found', '', 404);
        }else{
            return $this->getResponse('data found', $data, 200);
        }
    }

    public function getUsersWithBalance(){
        $data = User::with('usersBalance')->get();

        if($data->isEmpty()){
            return $this->getResponse('data is empty', '', 404);
        }else{
            return $this->getResponse('data found', $data, 200);
        }
    }

    public function getUserByIdWithBalance($id){
        $data = User::with('usersBalance')->find($id);

        if($data == null){
            return $this->getResponse('data not found', '', 404);
        }else{
            return $this->getResponse('data found', $data, 200);
        }
    }

    public function deleteUser($id){
        $data = User::find($id);

        if(is_null($data)){
            return $this->getResponse('data not found', '', 404);
        }else{
            try{
                $data->delete();
                return $this->getResponse('data is deleted', '', 200);
            }catch (Exception $e){
                return $this->getResponse($e->getMessage(), '', 500);
            }
        }
    }

}
