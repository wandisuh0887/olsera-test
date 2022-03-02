<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PajakItem extends Model
{
    use HasFactory;

    protected $table = 'pajak_item';

    protected $fillable = [
        'item_id','pajak_id'
    ];
}
