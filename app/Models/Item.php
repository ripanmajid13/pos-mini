<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    public function incomingItems()
    {
        return $this->hasMany(IncomingItem::class);
    }

    public function outgoingItems()
    {
        return $this->hasMany(OutgoingItem::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class)->withTrashed();
    }

    public function type()
    {
        return $this->belongsTo(Type::class)->withTrashed();
    }

    public function stock($id, $by = false)
    {
        if ($by) {
            return Item::find($id)->incomingItems->sum('qty')-Item::find($id)->outgoingItems->whereNotIn('id', [$by])->sum('qty');
        } else {
            return Item::find($id)->incomingItems->sum('qty')-Item::find($id)->outgoingItems->sum('qty');
        }
    }
}
