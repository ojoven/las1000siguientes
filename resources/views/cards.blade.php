<?php foreach ($cards as $card) { ?>

    @include('cards/word', ['word' => $card])

<?php }