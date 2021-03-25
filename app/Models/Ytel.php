<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ytel extends Model
{
    use HasFactory;
    protected $connection = 'mysql3';
    protected $table = 'vicidial_closer_log';

    public function ytel_vicidial_list()
    {
    	return $this->hasOne(YtelVicidialList::class, 'lead_id', 'lead_id')->where('security_phrase', '=', 'Sales')
    				->where('user','=',$this->user);
    }
}
