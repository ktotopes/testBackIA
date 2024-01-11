<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends MainModel
{
    protected $guarded = [];

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }
}
