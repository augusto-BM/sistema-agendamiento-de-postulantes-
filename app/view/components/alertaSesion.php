<?php
    if (isset($_SESSION['exitoso']) && $_SESSION['exitoso'] != "") {
        ?>
        <script>
          async function showSuccess() {
            await Swal.fire({
              position: "center",
              icon: "success",
              text: "<?php echo $_SESSION['exitoso']; ?>",
              showConfirmButton: false,
              timer: 2000,
              toast: true, // Asíncrono tipo toast
              animation: true, // Añadir animación
              showClass: {
                popup: 'animate__animated animate__fadeInUp' 
              },
              hideClass: {
                popup: 'animate__animated animate__fadeOutDown' 
              }
            });
          }

          showSuccess(); 
        </script>
        <?php
        unset($_SESSION['exitoso']);
    }

    if (isset($_SESSION['error']) && $_SESSION['error'] != "") {
        ?>
        <script>
          async function showError() {
            await Swal.fire({
              position: "center",
              icon: "error",
              text: "<?php echo $_SESSION['error']; ?>",
              showConfirmButton: true,
              animation: true,
              showClass: {
                popup: 'animate__animated animate__bounceIn' 
              },
              hideClass: {
                popup: 'animate__animated animate__fadeOut' 
              }
            });
          }
          showError(); 
        </script>
        <?php
        unset($_SESSION['error']);
    }
?>
