<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;
    protected $connection = 'mysql2';


}
