<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <link
          type="text/css"
          rel="stylesheet"
          href="https://unpkg.com/bootstrap/dist/css/bootstrap.min.css"
        />
        <link
          type="text/css"
          rel="stylesheet"
          href="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.css"
        />

        <!-- Required scripts -->
        <script src="https://unpkg.com/vue"></script>
        <script src="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.js"></script>

</head>
<body>
    <div id="app"></div>
</body>
</html> 