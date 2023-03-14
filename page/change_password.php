<?php
    ob_start();
    require_once "./connectDB.php";

    active_account($_SESSION['username']);
    update_password($_SESSION['username'],$password);
    echo "
        <script type=\"text/javascript\">
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.modal-password').style.display = 'none'
        });
        </script>
    ";

    session_destroy();
    header("Location: ./login.php");
    exit;

?>