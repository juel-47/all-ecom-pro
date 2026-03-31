<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Danish gift items - Danish Souvenirs, Viking Souvenirs, Ducky Memories, Duck Haven and Hygge Cotton.">
    <meta name="keywords" content="viking souvenirs, danish gifts, souvenirs, denmark">
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

    <title inertia>{{ $settings->site_name ?? 'Danish' }}</title>

    {{-- Fonts & Performance --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset($logoSetting?->favicon ?? '') }}">

    @viteReactRefresh
    @routes
    @vite(['resources/js/app.jsx'])
    @inertiaHead
</head>

<body>
    @inertia
</body>

</html>
