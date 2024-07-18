<!DOCTYPE html>
<html>
<head>
    <title>Resultados do Campeonato</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        h2 {
            color: #555;
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
            text-align: center;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
            margin: 10px 0;
            text-align: center;
        }
        hr {
            border: 0;
            height: 1px;
            background: #ddd;
            margin: 20px 0;
        }
        .winner {
            font-weight: bold;
            color: #2e8b57;
        }
        .loser {
            color: #a52a2a;
        }
        .penalties {
            font-style: italic;
            color: #888;
        }
    </style>
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