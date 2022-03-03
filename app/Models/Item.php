<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'item';

    protected $fillable = [
       'nama'
    ];

    protected $hidden = ['created_at','updated_at'];

    public function pajak()
    {
        return $this->belongsToMany(Pajak::class, 'pajak_item', 'item_id', 'pajak_id');
    }
}
