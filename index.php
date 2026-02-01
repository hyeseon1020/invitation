<?php
session_start();

if (isset($_SESSION['admin_id'])) {
  header("Location: admin/invite_list.php");
  exit;
} else {
  header("Location: admin/login.php");
  exit;
}
