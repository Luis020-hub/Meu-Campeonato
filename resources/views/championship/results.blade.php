<!DOCTYPE html>
<html>
<head>
    <title>Resultados do Campeonato</title>
    <link rel="stylesheet" href="{{ asset('results.css') }}">
</head>
<body>
    <h1>Resultados do Campeonato</h1>
    @foreach ($rounds as $roundName => $games)
        <h2>{{ ucfirst($roundName) }}</h2>
        @foreach ($games as $game)
            <p>
                {{ $game['team1']['name'] }} ({{ $game['team1']['goals'] }}) 
                vs 
                ({{ $game['team2']['goals'] }}) {{ $game['team2']['name'] }}
                @if(isset($game['penalties']))
                    <br>
                    Penaltis: {{ $game['penalties']['team1'] }} - {{ $game['penalties']['team2'] }}
                @endif
                <br>
                Perdedor: {{ $game['loser']['name'] }}
                <br>
                <b>Vencedor: {{ $game['winner']['name'] }}</b>
            </p>
        @endforeach
    @endforeach
    <a href="/">Voltar</a>
</body>
</html>