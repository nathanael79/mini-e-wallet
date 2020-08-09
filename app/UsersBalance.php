<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class UsersBalance extends Model
{
    /**
     * @var string
     */
    protected $table = 'users_balance';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function users(){
        return $this->hasOne(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usersBalanceHistory(){
        return $this->hasMany(UsersBalanceHistory::class);
    }

}
