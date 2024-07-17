<!DOCTYPE html>
<html>
<head>
    <title>Meu Campeonato</title>
</head>
<body>
    <h1>Insira os Times</h1>
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="/simulate" method="POST">
        @csrf
        @for ($i = 0; $i < 8; $i++) <label for="team{{$i}}">Time {{$i + 1}}</label>
            <input type="text" name="teams[]" id="team{{$i}}" required>
            <br>
        @endfor
        <button type="submit">Simular Campeonato</button>
    </form>
</body>
</html>