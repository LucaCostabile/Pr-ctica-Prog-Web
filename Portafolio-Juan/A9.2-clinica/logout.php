<?php
require __DIR__ . '/auth.php';
session_destroy();
header('Location: /clinica2/login.php');
