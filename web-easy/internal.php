<?php
session_start();

// Only allow internal access if request comes from challenge page
if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'challenge.php') !== false){
    $_SESSION['internal_access'] = true;
    echo "Internal Access Granted";
} else {
    echo "Forbidden";
}
?>
