{{-- Login dilakukan via modal di landing page, file ini adalah fallback yang redirect kembali --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="0; url={{ url('/') }}">
    <title>Login - Redirect</title>
</head>
<body>
    <script>
        window.location.href = "{{ url('/') }}";
    </script>
    <p>Redirecting to home page... <a href="{{ url('/') }}">Click here</a> if not redirected.</p>
</body>
</html>

