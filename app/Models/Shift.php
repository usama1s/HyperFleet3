<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    public function getName($id){
        $shift = Shift::find($id);
        if( !is_null( $shift)){
            return $shift->name;
        }else{
            return null;
        }
    }

    public function getStart($id){
        $shift = Shift::find($id);
        if( !is_null( $shift)){
            return date("g:i a", strtotime($shift->start));
        }else{
            return null;
        }
    }

    public function getEnd($id){
        $shift = Shift::find($id);
        if( !is_null( $shift)){
            return date("g:i a", strtotime($shift->end));
        }else{
            return null;
        }
    }
}
