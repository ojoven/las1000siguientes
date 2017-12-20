<!DOCTYPE html>
<html lang="en">

    <head>
        <!-- LE META -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- LE TITLE -->
        <title>Las 1000 siguientes</title>

        <!-- LE STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Amatic+SC|Raleway" rel="stylesheet">
        <link href="/css/style.css?v=5" rel="stylesheet">

    </head>

    <body draggable="false">

        <!-- GOOGLE ANALYTICS -->
        @include('partials/ga')

        <!-- HEADER -->
        @include('partials/header')

        <!-- VIEWPORT -->
        <div id="viewport">

            <!-- CARDS -->
            <ul class="cards">

                <!-- WELCOME CARD -->
                @include('partials/welcome')

            </ul>

            <!-- LOADING -->
            @include('partials/loading')

            <!-- FAV/TRASH BUTTONS (DESKTOP) -->
            @include('partials/favtrashbuttons')

        </div>

        <!-- SINGLE WORD POPUP -->
        @include('partials/single-word')

        <!-- FOOTER -->
        @include('partials/footer')

        <script type="text/javascript" src="/js/app.min.js?v=4"></script>

    </body>

</html>
