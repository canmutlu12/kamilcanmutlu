<?php
function checkStringDate($string) {
    if (strtotime($string)) {
        return true;
    }
    else {
        return false;
    }
}
