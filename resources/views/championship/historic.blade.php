<!DOCTYPE html>
<html>
<head>
    <title>Championship History</title>
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Championship History</h1>
        @foreach ($championships as $championship)
        <div class="championship">
            <h2><a href="/historic/{{ $championship->id }}">Championship #{{ $championship->id }}</a>
                <form action="{{ route('championship.destroy', $championship->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background:none; border:none; padding:0; cursor:pointer;">
                        <i class="fa fa-trash" style="color: red;"></i>
                    </button>
                </form>
            </h2>
            <div class="rounds container">
                @foreach ($championship->games->groupBy('round') as $roundName => $games)
                @if ($roundName == 'Final' || $roundName == 'ThirdPlace')
                @continue
                @endif
                <div class="round">
                    <h3>{{ ucfirst($roundName) }}</h3>
                    @foreach ($games as $game)
                    <p>
                        <b>{{ $game->host->name }} ({{ $game->host_goals }})
                            vs
                            ({{ $game->guest_goals }}) {{ $game->guest->name }}</b>
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
                @if ($championship->games->where('round', 'ThirdPlace')->count() > 0)
                <div class="round">
                    <h3>Third Place</h3>
                    @foreach ($championship->games->where('round', 'ThirdPlace') as $game)
                    <p>
                        <b>{{ $game->host->name }} ({{ $game->host_goals }})
                            vs
                            ({{ $game->guest_goals }}) {{ $game->guest->name }}</b>
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
                @endif
                <div class="round">
                    <h3>Final</h3>
                    @foreach ($championship->games->where('round', 'Final') as $game)
                    <p>
                        <b>{{ $game->host->name }} ({{ $game->host_goals }})
                            vs
                            ({{ $game->guest_goals }}) {{ $game->guest->name }}</b>
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
                <div class="ranking">
                    <h3>Ranking</h3>
                    <p><b>1st Place <br> {{ $championship->ranking['1st'] }}</b></p>
                    <p><b>2nd Place <br> {{ $championship->ranking['2nd'] }}</b></p>
                    <p><b>3rd Place <br> {{ $championship->ranking['3rd'] }}</b></p>
                </div>
            </div>
        </div>
        @endforeach
        <div class="buttons">
            <p>
                <a href="/">Back</a>
            </p>
        </div>
    </div>
</body>
</html>