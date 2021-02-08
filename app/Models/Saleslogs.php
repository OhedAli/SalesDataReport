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
    public function SalesFindByDate(array $date){

    }
}
