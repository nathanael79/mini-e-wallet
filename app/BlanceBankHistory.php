<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class BlanceBankHistory extends Model
{
    /**
     * @var string
     */
    protected $table = 'blance_bank_history';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blanceBankHistory(){
        return $this->hasMany(BlanceBankHistory::class);
    }

}
