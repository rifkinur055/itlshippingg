<?php
session_start();
session_unset();
session_destroy();
header('Location: /app_shipping/login.php');
exit;
