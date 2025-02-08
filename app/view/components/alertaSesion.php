<?php
    if (isset($_SESSION['exitoso']) && $_SESSION['exitoso'] != "") {
        ?>
        <script>
          Swal.fire({
            position: "center",
            icon: "success",
            text: "<?php echo $_SESSION['exitoso']; ?>",
            showConfirmButton: false,
            timer: 1500,
          });
        </script>
        <?php
        unset($_SESSION['exitoso']);
    }

    if (isset($_SESSION['error']) && $_SESSION['error'] != "") {
        ?>
        <script>
          Swal.fire({
            position: "center",
            icon: "error",
            text: "<?php echo $_SESSION['error']; ?>",
            showConfirmButton: true,
          });
        </script>
        <?php
        unset($_SESSION['error']);
    }
?>