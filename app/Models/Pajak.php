<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pajak extends Model
{
    use HasFactory;

    protected $table = 'pajak';

    protected $fillable = [
       'nama','rate'
    ];

    protected $hidden = ['created_at','updated_at','pivot'];

    public function pajak()
    {
        return $this->belongsToMany(Item::class, 'pajak_item', 'pajak_id', 'item_id');
    }
}
