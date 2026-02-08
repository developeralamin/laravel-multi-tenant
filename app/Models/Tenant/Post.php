<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model; 

class Post extends Model
{
    protected $connection = 'tenant';

    protected $guarded = [];
}
