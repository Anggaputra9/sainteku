<!DOCTYPE html>
<html>
<head>
    <title>Test Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>Authentication Test Page</h3>
        </div>
        <div class="card-body">
            @if(Auth::check())
                <div class="alert alert-success" role="alert">
                    <h4>✓ Authenticated!</h4>
                    <p><strong>User ID:</strong> {{ Auth::id() }}</p>
                    <p><strong>User Name:</strong> {{ Auth::user()->name }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                </div>
                <a href="/masterdata" class="btn btn-primary">Go to MasterData</a>
                <form method="POST" action="/logout" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            @else
                <div class="alert alert-warning" role="alert">
                    <h4>✗ Not Authenticated</h4>
                    <p>You need to login first.</p>
                </div>
                <a href="/" class="btn btn-primary">Go to Login</a>
            @endif
        </div>
    </div>
</div>
</body>
</html>
