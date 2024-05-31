<!DOCTYPE html>
<html>
<head>
    <title>BotanyBuddy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: transform 0.3s;
            margin-bottom: 1.5rem;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-header {
            background-color: #17a2b8;
            color: #fff;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            font-size: 1.25rem;
            font-weight: bold;
            text-align: center;
            padding: 1rem;
        }
        .card-body {
            padding: 2rem;
        }
        .card-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .card-text {
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }
        .img-thumbnail {
            margin-bottom: 1rem;
            transition: transform 0.3s;
        }
        .img-thumbnail:hover {
            transform: scale(1.05);
        }
        .alert {
            margin-top: 1rem;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
            .card-body {
                padding: 1.5rem;
            }
        }
        /* Navigation Bar */
        .navbar {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #28a745 !important;
        }
        .navbar-nav .nav-link {
            font-size: 1rem;
            font-weight: 500;
            color: #495057 !important;
            transition: color 0.3s;
        }
        .navbar-nav .nav-link:hover {
            color: #28a745 !important;
        }
        .navbar-toggler {
            border: none;
        }
        .navbar-toggler:focus {
            outline: none;
            box-shadow: none;
        }
        .navbar-toggler-icon {
            color: #28a745;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">BotanyBuddy</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">&#9776;</span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('index') }}">Scan Plant</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('plant.display') }}">Saved Plants</a>
                </li>
            </ul>
        </div>
    </nav>
    @if (isset($facts) && !empty($facts))
    <div class="container mt-5">
        <div class="row">
            @foreach ($facts as $fact)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Random Plant Fact
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Plant Fact:</h5>
                            <p class="card-text">{{ $fact['fact'] }}</p>
                            @if ($fact['imageUrl'])
                                <h6>Plant Image:</h6>
                                <div class="mb-3">
                                    <img src="{{ $fact['imageUrl'] }}" class="img-thumbnail" alt="Plant Image">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</body>
</html>
