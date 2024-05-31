

<!DOCTYPE html>
<html>
<head>
    <title>Upload Plant Image</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-header {
            background-color: #28a745;
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
        .form-group label {
            font-weight: bold;
        }
        .form-group input[type="file"] {
            display: none;
        }
        .form-group input[type="file"] + label {
            border: 1px solid #ced4da;
            display: inline-block;
            padding: 0.5rem 1rem;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .form-group input[type="file"] + label:hover {
            background-color: #e9ecef;
        }
        .form-group img {
            display: none;
            margin-top: 1rem;
            max-width: 100%;
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .alert {
            margin-top: 1rem;
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fileInput = document.getElementById('image');
            const fileLabel = document.querySelector('label[for="image"]');
            const previewImage = document.createElement('img');

            fileLabel.insertAdjacentElement('afterend', previewImage);

            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        previewImage.setAttribute('src', event.target.result);
                        previewImage.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewImage.style.display = 'none';
                }
            });
        });
    </script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
    
@if (session('success'))

                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
            @endif
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
                    <a class="nav-link" href="{{ route('plant.display') }}">Saved Plants</a>
                </li>
            </ul>
        </div>
    </nav>
   

        
     
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                Upload Plant Image
            </div>
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <form action="{{ route('plant.result') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="file" id="image" name="image" required>
                        <label for="image" class="btn btn-outline-secondary">Select Image</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Identify Plant</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
