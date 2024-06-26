<!--------------- BOOTSTRAP JS --------------->
<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
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
</body>
</html>