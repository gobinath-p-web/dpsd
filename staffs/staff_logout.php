<?php
session_start();
session_unset();
session_destroy();
header("Location: staff_index.html");
exit();
?>