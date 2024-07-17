<!DOCTYPE html>
<html>
<head>
    <title>Resultados do Campeonato</title>
</head>
<body>
    <h1>Resultados do Campeonato</h1>
    @foreach($rounds as $roundName => $games)
        <h2>{{ ucfirst($roundName) }}</h2>
        @foreach($games as $gameData)
            <p>{{ $gameData['game']['team1'] }} {{ $gameData['game']['score1'] }} x {{ $gameData['game']['score2'] }} {{ $gameData['game']['team2'] }}</p>
        @endforeach
    @endforeach
</body>
</html>