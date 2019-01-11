<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ env('PAGE_TITLE') ?: config('app.name', 'LinkAce') }}</title>

<link href="{{ asset('assets/dist/css/app.css') }}" rel="stylesheet">
<link href="{{ asset('assets/dist/css/fa.min.css') }}" rel="stylesheet">
