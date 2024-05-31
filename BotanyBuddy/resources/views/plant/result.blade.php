<!DOCTYPE html>
<html>
<head>
    <title>Plant Identification Result</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                Plant Identification Result
            </div>
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @elseif (session('message'))
                    <div class="alert alert-info">
                        {{ session('message') }}
                    </div>
                @else
                    @isset($identifiedPlantName)
                        <h5 class="card-title">Identified Plant: {{ $identifiedPlantName }}</h5>
                        <p class="card-text">Probability: {{ $probability }}</p>
                        <h6>Plant Description:</h6>
                        <p>{{ $plantDescription }}</p>
                        <h6>Uploaded Image:</h6>
                        <div class="mb-3">
                            <img src="data:image/jpeg;base64,{{ $imageContent }}" class="img-thumbnail" alt="Uploaded Image">
                        </div>
                        <h6>Similar Images:</h6>
                        <div class="row">
                            @foreach($similarImages as $image)
                                <div class="col-md-3">
                                    <img src="{{ $image['url'] }}" class="img-thumbnail" alt="Similar Image">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>No plant identification results available.</p>
                    @endisset
                @endif
            </div>
        </div>
    </div>

    <form action="{{ route('plant.save') }}" method="POST">
        @csrf
        <input type="hidden" name="identifiedPlantName" value="{{ $identifiedPlantName }}">
        <input type="hidden" name="probability" value="{{ $probability }}">
        <input type="hidden" name="plantDescription" value="{{ $plantDescription }}">
        <input type="hidden" name="similarImages" value="{{ json_encode($similarImages) }}">
        <button type="submit" class="btn btn-success">Save Plant</button>
    </form>
</body>
</html>
