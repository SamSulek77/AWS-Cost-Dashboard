<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AwsCost extends Model
{
    protected $table = 'aws_costs';
    public $timestamps = false;

    protected $fillable = [
        'LinkedAccountName',
        'totalCost',
        'UsageEndDate',
    ];
}
