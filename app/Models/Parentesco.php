<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parentesco extends Model
{
    use HasFactory;
    public function __construct() {
        $this->setTable( 'parentesco');
        $this->setKeyName('idparentesco');
    }
    
}
