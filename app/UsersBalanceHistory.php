<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class UsersBalanceHistory extends Model
{
    /**
     * @var string
     */
    protected $table = 'users_balance_history';

    /**
     * @var array
     */
    protected $fillable = [
        'user_balance_id',
        'balance_before',
        'balance_after',
        'activity',
        'type'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function usersBalance(){
        return $this->hasOne(UsersBalance::class);
    }

}
