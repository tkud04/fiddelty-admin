<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Accounts extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $fillable = [
        'user_id', 'name', 'type', 'amount', 'date', 'tz', 'status'
    ];
}