<!DOCTYPE html>
<html lang=" str_replace('_', '-', app()->getLocale()) ">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <!-- <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" /> -->
  <!-- <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet"> -->
  
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Alexandria:wght@400;500;700&display=swap" />
  <link href="https://fonts.googleapis.com/css2?family=Readex+Pro:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
  <title>Laundry - POS Dashboard</title>
</head>

<body>
  <div id="app"></div>
  @vite('resources/js/app.js')
  <!-- <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script> -->
</body>

</html>