<?php
/** ==================================================
 * STANDARD WORD CARD
 ===================================================== **/
?>

<li class="card word-card show-popup" data-word="<?php echo $word['id']; ?>">
    <div class="title">
        <?php echo $word['word']; ?>
    </div>
    <iframe src="/data/<?php echo $word['word']; ?>.html"></iframe>
</li>