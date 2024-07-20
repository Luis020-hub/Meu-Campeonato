<!DOCTYPE html>
<html>
<head>
    <title>Meu Campeonato</title>
    <link rel="stylesheet" href="{{ asset('index.css') }}">
</head>
<body>
    <div class="container">
        <h1>Enter Teams</h1>
        @if ($errors->any())
        <div class="error-messages">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form action="/simulate" method="POST">
            @csrf
            @for ($i = 0; $i < 8; $i++) <label for="team{{$i}}">Team {{$i + 1}}</label>
                <input type="text" name="teams[]" id="team{{$i}}" required>
                @endfor
                <div class="buttons">
                    <button type="submit">Simulate</button>
                    <a href="/historic">History</a>
                </div>
        </form>
    </div>
</body>
</html>