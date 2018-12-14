<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Dometic DMS">
        <link rel="apple-touch-icon" sizes="57x57" href="ico/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="114x114" href="ico/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="72x72" href="ico/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/ico/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/ico/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/ico/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/ico/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/ico/apple-touch-icon-152x152.png">
        <link rel="icon" type="image/png" href="/ico/favicon-196x196.png" sizes="196x196">
        <link rel="icon" type="image/png" href="/ico/favicon-160x160.png" sizes="160x160">
        <link rel="icon" type="image/png" href="/ico/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="/ico/favicon-16x16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="/ico/favicon-32x32.png" sizes="32x32">
        <meta name="msapplication-TileColor" content="#2b5797">
        <meta name="msapplication-TileImage" content="ico/mstile-144x144.png">
        
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
        <!-- Latest compiled and minified CSS -->
        <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        
        
        <!-- Scripts -->
        @if(Auth::guard('dms')->check() || Auth::guard('hotel_user')->check())
            <script src="{{ asset('js/admin.js') }}" defer></script>
            <script>
                const AJAX_URI = '{{ $AJAX_URI }}';
            </script>
        @else
            <script src="{{ asset('js/app.js') }}" defer></script>
        @endif
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>
        <script src="{{ asset('js/toastr.min.js') }}"></script>
        
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
    <body>