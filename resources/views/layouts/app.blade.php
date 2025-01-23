<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kahunas')</title>

    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}"> <!-- Your custom CSS file -->

    <!-- Optionally, add a CSS framework (e.g., Bootstrap) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Optionally, add internal styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .container {
            padding: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to My App</h1>
        <!-- Navigation or header content goes here -->
    </header>

    <div class="container">
        <!-- Main content goes here -->
        @yield('content')
    </div>

    {{-- <footer>
        <p>© 2025 My App. All rights reserved.</p>
    </footer> --}}

    <!-- Optional: Add JavaScript files -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
