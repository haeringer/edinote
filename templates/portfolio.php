<ul class="nav nav-pills">
    <li><a href="quote.php">Quote</a></li>
    <li><a href="buy.php">Buy</a></li>
    <li><a href="sell.php">Sell</a></li>
    <li><a href="history.php">History</a></li>
    <li><a href="deposit.php">Deposit</a></li>
    <li><a href="account.php">Account</a></li>
    <li><a href="logout.php"><strong>Log Out</strong></a></li>
</ul>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Symbol</th>
            <th>Shares</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="4">Cash</td>
            <td>$<?= $cash ?></td>
        </tr>
        <?php foreach ($positions as $position): ?>

        <tr>
            <td><?= $position["name"] ?></td>
            <td><?= $position["symbol"] ?></td>
            <td><?= $position["shares"] ?></td>
            <td>$<?= sprintf("%0.2f", $position["price"]) ?></td>
            <td>$<?= sprintf("%0.2f", $position["price"] * $position["shares"]) ?></td>
        </tr>

        <?php endforeach ?>
    </tbody>
</table>
