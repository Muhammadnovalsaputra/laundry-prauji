<?php

    // isset : ada atau tidak kosong
    // jika session kosong
    function checklogin()
    {
        if(!isset($_SESSION['ID'])) {
            header("location:login?access=failed");
        }
    }

?>