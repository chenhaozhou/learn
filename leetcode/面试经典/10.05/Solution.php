<?php

class Solution {
    /**
     * @param String[] $words
     * @param String $s
     * @return Integer
     */
    function findString($words, $s) {
        $left = 0;
        $right = count($words)-1;

        while($left<=$right){
            $tmp = $mid = floor(($left+$right)/2);
            while($words[$tmp] == '' && $tmp<$right){
                $tmp++;
            }
            if($words[$tmp] == ''){
                $right = $mid-1;
                continue;
            }
            $mid = $tmp;
            if($words[$mid] == $s){
                return $mid;
            }elseif($words[$mid] < $s){
                $left = $mid+1;
            }else{
                $right = $mid-1;
            }
        }
        return -1;
    }
}