<?php
echo json_encode($_POST);
session_start();
print_r($_SESSION['user']);
