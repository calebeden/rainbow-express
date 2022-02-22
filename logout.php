<?php
require_once "includes/session.php";
unset($_SESSION['user']);
session_regenerate_id(); // not quite sure if this is needed here but would rather be safe than sorry
session_destroy();
header('Location: ./login.php');
exit;
