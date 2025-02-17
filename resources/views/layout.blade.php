<!doctype html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ish maydoni - OnlyOffice</title>
    <link rel="shortcut icon" href="{{ '/vendor/office/assets/favicon.png' }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ '/vendor/office/assets/css/bootstrap.min.css' }}">
</head>
<body>
    <div id="app" class="mt-5">
        <div id="main" class="container-fluid">
            <div class="row vh-100">
                @yield('content')
            </div>
            <div class="mt-5"></div>
        </div>
    </div>
    @yield('js')
</body>
</html>