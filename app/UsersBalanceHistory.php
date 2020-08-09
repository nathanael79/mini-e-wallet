<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class UsersBalanceHistory extends Model
{
    /**
     * @var string
     */
    protected $table = 'users_balance_histoy';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function usersBalance(){
        return $this->hasOne(UsersBalance::class);
    }

}
