<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responsavel_aluno extends Model
{
    use HasFactory;
    public function __construct() {
        $this->setTable( 'responsavel_aluno');
        $this->setKeyName('idresponsavel_aluno');
        $this->timestamps = false;
    }
    
}
