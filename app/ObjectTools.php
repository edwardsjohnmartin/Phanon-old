<?php
namespace App;

/**
 * Class of methods to use for all objects
 * @version 1.0
 * @author holmjona
 */
class ObjectTools
{
    /**
     * Gets the index of the object in the array that has the given id
     * @param mixed $coll Collection/Array to search
     * @param mixed $id ID to find.
     * @return integer index of the element with the given ID; -1 if not found.
     */
    public static function getIndex($coll, $id){
        $index = 0;
        $retIndex = -1;
        foreach($coll as $con){
            if($con->id == $id) {
                $retIndex = $index;
                break;
            }
            $index++;
        }
        return $retIndex;
    }

    public static function getItem($coll,$attr, $val){
        if (!is_array($attr)){
            $attr = [$attr]; // make array if only single value
        }
        if(!is_array($val)){
            $val = [$val];// make array if only single value
        }

        $retItem = null;
        if (count($attr) == count($val)){
            // all good.
            foreach($coll as $con){
                $allMatch = true;
                for($ia = 0 ; $ia < count($attr); $ia++){
                    if($con->{$attr[$ia]} == $val[$ia]) {
                        $allMatch = $allMatch && true;
                    }else{
                        $allMatch = false;
                    }
                }
                if($allMatch){
                    $retItem = $con;
                    break;
                }
            }
        }else{
            // oops mismatch.
        }


        return $retItem;
    }

}