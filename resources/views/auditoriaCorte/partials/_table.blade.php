@foreach ($DatoAXNoIniciado as $inicio)
<tr>
    <td><a href="{{ route('auditoriaCorte.altaAuditoriaCorte', ['orden' => $inicio->op]) }}" class="btn btn-primary">Acceder</a></td>
    <td>{{ $inicio->op }}</td>
    <td>{{ $inicio->estilo }}</td>
</tr>
@endforeach
