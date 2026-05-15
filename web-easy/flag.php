<?php
session_start();

$flag = "TeXSS{Reflected_XSS_Chained_Successfully}";

if(isset($_SESSION['internal_access']) && $_SESSION['internal_access'] === true){
    echo $flag;
} else {
    echo "Access Denied";
}
?>
