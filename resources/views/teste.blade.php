<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<div class="container">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Matricula</th>
            <th>CPF</th>
            <th>nome</th>
            <th>ds_verba_arquivo</th>
            <th>Solicitacao</th>

        </tr>
        </thead>
        <tbody>

        @forelse($arquivos as $arquivo)
            <tr>
                <td>{{$arquivo->pessoa->servidores->where('nr_matricula',$arquivo->matricula)->first()->nr_matricula}}</td>
                <td>{{$arquivo->cpf}}</td>
                <td>{{$arquivo->pessoa->nm_pessoa}}</td>
                <td>{{$arquivo->cod_verba}}</td>
                @php


                    $verbareal = $verbas->where('ds_verba',$arquivo->cod_verba)->first()->cd_verba;
                    //dd($verbareal);


                @endphp
                <td>{{$arquivo->pessoa->servidores->where('nr_matricula',$arquivo->matricula)->first()->solicitacaos->pluck('cd_consignataria')}}</td>
            </tr>
        @empty
        @endforelse

        <tr>
            <td>Mary</td>
            <td>Moe</td>
            <td>mary@example.com</td>
        </tr>
        <tr>
            <td>July</td>
            <td>Dooley</td>
            <td>july@example.com</td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
