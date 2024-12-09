<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function pendings()
    {
        return $this->hasMany(Pending::class);
    }

    public function managedCategory()
    {
        return $this->belongsTo(ManagedCategory::class);
    }


}
