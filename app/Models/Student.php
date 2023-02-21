<?php

namespace App\Models;

use App\Models\Statistic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $connection = 'mysql_tcde_docs';

    protected $table = 'StudentCard';

    public function getFio() {
        return $this->StudentLastName.' '.$this->StudentFirstName.' '.$this->StudentSecondName;
    }
}
