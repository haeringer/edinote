<?php if ($pw_success == true) : ?>

    <div>
    <p>Password has been changed!</p>
    </div>

<?php else : ?>

    <div>
    <p>&nbsp;</p>
    </div>

<?php endif; ?>

<form action="account.php" method="post">
    <fieldset>
        <div class="form-group">
            <input class="form-control" name="password" placeholder="New password" type="password"/>
        </div>
        <div class="form-group">
            <input class="form-control" name="confirmation" placeholder="Confirmation" type="password"/>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-default">Change Password</button>
        </div>
    </fieldset>
</form>
