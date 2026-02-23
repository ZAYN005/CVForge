<?php
session_start();
session_unset();
session_destroy();
header("Location: http://localhost/cvforge_frontend/index.html");
exit;
?>
