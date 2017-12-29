<?php
/** ==================================================
 * STANDARD WORD CARD
 ===================================================== **/
?>

<li class="card word-card show-popup" data-word="<?php echo $word['id']; ?>">
    <div class="title">
        <?php echo $word['word']; ?>
    </div>
    <div class="definitions">
        <ul>
            <?php foreach($word['definitions'] as $definition) {
                if ($definition['featured']) { ?>
                <li><span><?php echo $definition['definition']; ?></span></li>
                <?php } ?>
            <?php } ?>
        </ul>
    </div>
</li>