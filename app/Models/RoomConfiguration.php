<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomConfiguration extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'hotel_id',
        'room_type',
        'accommodation',
        'quantity'
    ];
    
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
