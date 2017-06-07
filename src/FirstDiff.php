<?php

namespace Graze\BufferedConsole;

class FirstDiff
{
    /**
     * Loop through each line, and produce a difference report of what to write out to the terminal
     *
     * Assumes the beginning of old and new are the same line
     *
     * @param string[] $old
     * @param string[] $new
     *
     * @return array [[:col, :str]]
     */
    public function lines(array $old, array $new)
    {
        $out = [];
        for ($i=0; $i < count($old) || $i < count($new); $i++) {
            if ($i >= count($old)) {
                $out[] = ['col' => 0, 'str' => $new[$i]]; // write out the entire line for extra lines
            } elseif ($i >= count($new)) {
                $out[] = ['col' => 0, 'str' => '']; // clear the line if the new array has less entries than the old one
            } else {
                $col = $this->firstDifference($old[$i], $new[$i]);
                if ($col === -1) {
                    $out[] = null;
                } else {
                    $out[] = ['col' => $col, 'str' => mb_substr($new[$i], $col)];
                }
            }
        }
        return $out;
    }

    /**
     * Looks through a line to find the character to of the first difference between the 2 strings.
     *
     * If no difference is found, returns -1
     *
     * @param string $old
     * @param string $new
     *
     * @return int
     */
    public function firstDifference($old, $new)
    {
        // loop through old and new character by character and compare
        $oldLen = mb_strlen($old);
        $newLen = mb_strlen($new);

        if ($oldLen === 0) {
            return 0;
        }

        for ($i = 0; $i < $oldLen && $i < $newLen; $i++) {
            if (mb_substr($old, $i, 1) !== mb_substr($new, $i, 1)) {
                return $i;
            }
        }
        if ($i < $oldLen || $i < $newLen) {
            return $i;
        }
        return -1;
    }
}
