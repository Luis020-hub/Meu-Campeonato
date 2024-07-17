<!DOCTYPE html>
<html>
<head>
    <title>Resultados do Campeonato</title>
</head>
<body>
    <h1>Resultados do Campeonato</h1>
    @foreach($rounds as $roundName => $games)
        <h2>{{ ucfirst($roundName) }}</h2>
        @foreach($games as $game)
            <p>{{ $game->getTeam1()->getName() }} {{ $game->getScore1() }} x {{ $game->getScore2() }} {{ $game->getTeam2()->getName() }}</p>
        @endforeach
    @endforeach
</body>
</html>