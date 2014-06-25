<?php
/**
 * @var yii\web\View $this
 */
$this->title = 'Arinoid | Shorten URL';
?>
<div class="site-index">
    <form id="short_form" class="form-inline text-center">
        <div class="form-group">
            <label class="sr-only" for="link">URL</label>
            <input name="uri" type="text" class="form-control" autocomplete="off" size="50" id="uri"
                   placeholder="Enter link">
        </div>
        <button type="submit" class="btn btn-default">Shorten URL</button>

    </form>
    <?php //TODO: add design, copy to clipboard, qr-code ?>
    <a href="" id="short_uri" target="_blank"></a>
</div>
