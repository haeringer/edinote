<div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Transaction</th>
                <th>Symbol</th>
                <th>Shares</th>
                <th>Price</th>
                <th>Date/Time</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($positions as $position): ?>

            <tr>
                <td><?= $position["transaction"] ?></td>
                <td><?= $position["symbol"] ?></td>
                <td><?= $position["shares"] ?></td>
                <td>$<?= number_format($position["price"], 2) ?></td>
                <td><?= $position["datetime"] ?></td>
            </tr>

            <?php endforeach ?>
        </tbody>
    </table>
</div>
