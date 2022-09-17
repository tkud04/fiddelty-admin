<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Transactions extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $fillable = [
        'user_id', 'type','ttype','date', 'from', 'dt','amount', 'status'
    ];
}