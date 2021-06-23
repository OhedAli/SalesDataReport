<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saleslogs extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';

    public function SalesFind($date){

    }
    public function plan(){
        return $this->belongsTo(Plan::class);
    }

    public function slaesagent()
    {
        return $this->hasMany(Salesagent::class, 'user_alter_name', 'salesman');
    }

    public function team_lead_agent()
    {
        return $this->hasMany(Salesagent::class, 'manager_alter_name', 't_o')
                    ->where('user_group','=','TO')
                    ->where(function($query){
                        return $query->where('user_ytel_name','like','T.O. %')->orWhere('user_ytel_name','like','T O %');
                    });
    }
}
