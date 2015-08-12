<form action="sell.php" method="post">
  <fieldset>
    <div class="form-group">
      <select class="form-control" name="symbol">
        <option value="">&nbsp;</option>
        <?php foreach ($stocks as $stock): ?>

          <option value="<?= $stock['symbol'] ?>"><?= $stock["symbol"] ?></option>

        <?php endforeach ?>
      </select>
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-default">Sell</button>
    </div>
  </fieldset>
</form>
