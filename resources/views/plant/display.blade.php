<!DOCTYPE html>
<html>
<head>
    <title> BotanyBuddy</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding-top: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #17a2b8;
            color: #fff;
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
        .btn-delete {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }
        .btn-delete:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    const confirmed = confirm('Are you sure you want to delete this plant?');
                    if (!confirmed) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
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
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('index') }}">Scan Plant</a>
                </li>
            </ul>
        </div>
    </nav>
   
    <div class="container">
        <h1 class="mb-5 text-center">Saved Plants</h1>
        @foreach($plants as $plant)
            <div class="card">
                <div class="card-header">
                    {{ $plant->name }}
                </div>
                <div class="card-body">
                    <h5 class="card-title">Probability: {{ $plant->probability }}</h5>
                    <p class="card-text">{{ $plant->description }}</p>
                    <h6>Similar Images:</h6>
                    <div class="row">
                        @php
                            $similarImages = json_decode($plant->similar_images, true);
                        @endphp
                        @foreach($similarImages as $image)
                            <div class="col-md-3">
                                <img src="{{ $image['url'] }}" class="img-thumbnail" alt="Similar Image">
                            </div>
                        @endforeach
                    </div>
                </div>
       

                
                <div class="card-footer text-center">
                    <form action="{{ route('plant.delete', $plant) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

</body>
</html>
