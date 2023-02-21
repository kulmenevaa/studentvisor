<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suspicious extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'suspicious';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ip',
        'desc',
        'check',
        'place',
        'type'
    ];
}
