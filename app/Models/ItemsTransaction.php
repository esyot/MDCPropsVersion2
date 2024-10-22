<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemsTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

}
