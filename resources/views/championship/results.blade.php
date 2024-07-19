<!DOCTYPE html>
<html>
<head>
    <title>Championship</title>
    <link rel="stylesheet" href="{{ asset('results.css') }}">
</head>
<body>
    <div class="container">
        <h1>Championship</h1>
        <div class="rounds container">
            @foreach ($rounds as $roundName => $games)
            <div class="round">
                <h2>{{ ucfirst($roundName) }}</h2>
                @foreach ($games as $game)
                <p>
                    <b>{{ $game['host']['name'] }} ({{ $game['host']['goals'] }})
                        vs
                        ({{ $game['guest']['goals'] }}) {{ $game['guest']['name'] }}</b>
                    @if(isset($game['penalties']))
                    <br>
                    <b class="penalties">Penalty Shootout: {{ $game['penalties']['host'] }} - {{ $game['penalties']['guest'] }}</b>
                    @endif
                    <br>
                    <b class="loser">Loser: {{ $game['loser']['name'] }}</b>
                    <br>
                    <b class="winner">Winner: {{ $game['winner']['name'] }}</b>
                </p>
                @endforeach
            </div>
            @endforeach
            <h3>Championship Champion <br> <b>{{ $ranking['1st']['name'] }}</b></h3>
        </div>
        <h2>Ranking</h2>
        <p><b>1° Lugar: {{ $ranking['1st']['name'] }}</b></p>
        <p><b>2° Lugar: {{ $ranking['2nd']['name'] }}</b></p>
        <p><b>3° Lugar: {{ $ranking['3rd']['name'] }}</b></p>
        <div class="buttons">
            <p>
                <a href="/">Back</a>
                <a href="/historic">Historic</a>
            </p>
        </div>
    </div>
</body>
</html>