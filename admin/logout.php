<?php
session_start();
session_destroy();
header("Location: /shoe-store/admin/login.php");
exit;