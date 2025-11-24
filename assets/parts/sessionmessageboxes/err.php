<?php if (isset($_SESSION[$SESSIONNOTIF_ERR])): ?>

<div class="alert alert-danger" role="alert">
    <?= $_SESSION[$SESSIONNOTIF_ERR] ?>
</div>

<?php unset($_SESSION[$SESSIONNOTIF_ERR]); endif; ?>