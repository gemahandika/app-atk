<?php
session_name("dashboard_atk_session");
session_start();
session_destroy();
header("location:login.php");
