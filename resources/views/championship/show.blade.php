<!DOCTYPE html>
<html>
<head>
    <title>Championship Details</title>
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
</head>
<body>
    <div class="container">
        <h1>Championship #{{ $championship->id }}</h1>
        <div class="rounds container">
            @foreach ($rounds as $roundName => $games)
            <div class="round">
                <h2>{{ ucfirst($roundName) }}</h2>
                @foreach ($games as $game)
                <p>
                    <b>{{ $game->host }} ({{ $game->host_goals }})
                        vs
                        ({{ $game->guest_goals }}) {{ $game->guest }}</b>
                    @if($game->penalty_host_goals !== null && $game->penalty_guest_goals !== null)
                    <br>
                    <b class="penalties">Penalty Shootout: {{ $game->penalty_host_goals }} - {{ $game->penalty_guest_goals }}</b>
                    @endif
                    <br>
                    <b class="loser">Loser: {{ $game->loser }}</b>
                    <br>
                    <b class="winner">Winner: {{ $game->winner }}</b>
                </p>
                @endforeach
            </div>
            @endforeach
            <h3>Championship Champion <br> <b>{{ $ranking['1st'] }}</b></h3>
        </div>
        <h2>Ranking</h2>
        <p><b>1° Lugar: {{ $ranking['1st'] }}</b></p>
        <p><b>2° Lugar: {{ $ranking['2nd'] }}</b></p>
        <p><b>3° Lugar: {{ $ranking['3rd'] }}</b></p>
        <div class="buttons">
            <p>
                <a href="/">Back to start</a>
                <a href="/historic">Back to History</a>
            </p>
        </div>
    </div>
</body>
</html>