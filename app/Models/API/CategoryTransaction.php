<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTransaction extends Model
{
    use HasFactory;

    protected $table = 'category_transactions';

    protected $fillable = [
        'id_category_transaction',
        'name',
    ];
}
