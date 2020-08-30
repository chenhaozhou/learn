<?php

class Solution
{
    /**
     * @param String $s
     * @return Integer
     */
    function lengthOfLongestSubstring($s)
    {
        $set = [];
        $maxLen = $leftIdx = 0;

        for ($i = 0; $i < strlen($s); $i++) {
            if (key_exists($s[$i], $set)) {
                $maxLen = max($i - $leftIdx, $maxLen);
                $leftIdx = $set[$s[$i]] + 1;
                $set = [];

                for ($l = $leftIdx; $l <= $i; $l++) {
                    $set[$s[$l]] = $l;
                }

            } else {
                $set[$s[$i]] = $i;
            }
            echo 'i:' . $i . ' li:' . $leftIdx . ' m:' . $maxLen . PHP_EOL;
        }

        return max($i - $leftIdx, $maxLen);
    }

}

