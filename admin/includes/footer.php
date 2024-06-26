</main>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/perfect-scrollbar.min.js"></script>
<script src="../assets/js/smooth-scrollbar.min.js"></script>
<!--------------- ALERTIFY JS --------------->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script>
    alertify.set('notifier', 'position', 'top-right'); // Set notifier position to top-right

    <?php if(isset($_SESSION['success']) && !empty($_SESSION['success'])): ?>
        alertify.success('<?= $_SESSION['success']?>'); // Display success message
        <?php unset($_SESSION['success']); // Unset the session success message after displaying ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
        alertify.error('<?= $_SESSION['error']?>'); // Display error message
        <?php unset($_SESSION['error']); // Unset the session error message after displaying ?>
    <?php endif; ?>
</script>
<style>
    /* Customize AlertifyJS notifier */
    .alertify-notifier {
        border-radius: 20px!important; /* Adjust the border radius as needed */
        font-family: 'Poppins';
    }
</style>
</body>
</html>
<?php
ob_end_flush(); // Flush the output buffer and turn off output buffering
?>