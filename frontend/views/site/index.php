<?php
/**
 * @var yii\web\View $this
 */
$this->title = 'Arinoid | Shorten URL';
?>
<div class="site-index">
    <form class="form-inline text-center">
        <div class="form-group">
            <label class="sr-only" for="link">URL</label>
            <input name="uri" type="text" class="form-control" size="50" id="link" placeholder="Enter link">
        </div>
        <button type="submit" class="btn btn-default">Shorten URL</button>
    </form>
</div>
