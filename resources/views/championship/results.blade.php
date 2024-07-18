<!DOCTYPE html>
<html>
<head>
    <title>Championship</title>
    <link rel="stylesheet" href="{{ asset('results.css') }}">
</head>
<body>
    <h1>Championship</h1>
    @foreach ($rounds as $roundName => $games)
    <h2>{{ ucfirst($roundName) }}</h2>
    @foreach ($games as $game)
    <p>
        {{ $game['host']['name'] }} ({{ $game['host']['goals'] }})
        vs
        ({{ $game['guest']['goals'] }}) {{ $game['guest']['name'] }}
        @if(isset($game['penalties']))
        <br>
        Penalty Shootout: {{ $game['penalties']['host'] }} - {{ $game['penalties']['guest'] }}
        @endif
        <br>
        Loser: {{ $game['loser']['name'] }}
        <br>
        <b>Winner: {{ $game['winner']['name'] }}</b>
    </p>
    @endforeach
    @endforeach
    <h2>Ranking</h2>
    <p><b>1° Lugar:</b> {{ $ranking['1st']['name'] }}</p>
    <p><b>2° Lugar:</b> {{ $ranking['2nd']['name'] }}</p>
    <p><b>3° Lugar:</b> {{ $ranking['3rd']['name'] }}</p>
    <a href="/">Voltar</a>
</body>
</html>