<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function medicines(){
        return $this->belongsToMany(Medicine::class)
                ->withPivot("quantity")
                ->withTimestamps();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    protected $fillable = [
        "issueDate",
        "totalPrice",
        "totalQuantity"
    ];
}
