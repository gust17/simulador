<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <link href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
            crossorigin="anonymous"></script>


    <title>Document</title>
</head>
<body>

<div class="container">
    <table id="dados" class="table table-striped">
        <thead>
        <tr>
            <th>Matricula</th>
            <th>CPF</th>
            <th>nome</th>
            <th>ds_verba_arquivo</th>
            <th>Solicitacao</th>
            <th>Valor Info</th>
            <th>Valor Arquivo</th>

        </tr>
        </thead>
        <tbody>

        @forelse($arquivos as $arquivo)
            <tr>
                <td>{{$arquivo->pessoa->servidores->where('nr_matricula',$arquivo->matricula)->first()->nr_matricula}}</td>
                <td>{{$arquivo->cpf}}</td>
                <td>{{$arquivo->pessoa->nm_pessoa}}</td>
                <td>{{$arquivo->cod_verba}}</td>
                <td>@if(!empty($arquivo->solicitacao()))
                        {{$arquivo->solicitacao()->cd_solicitacao}}
                    @else
                        0
                    @endif</td>
                <td>@if(!empty($arquivo->solicitacao()))
                        {{$arquivo->solicitacao()->vl_parcela}}
                    @else
                        0
                    @endif</td>
                <td>{{$arquivo->valor_realizado}}</td>
            </tr>
        @empty
        @endforelse


        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script>
    $(document).ready(function () {

        $('#dados').DataTable(
            {

                dom: 'Bfrtip',
                buttons: [{
                    extend: 'csv',
                    text: 'Exportar para CSV',
                    charset: "utf8"
                },
                    {
                        extend: 'excel',
                        text: 'Exportar para Excel'
                    },
                    {
                        extend: 'pdf',
                        orientation: 'landscape',
                        text: 'Exportar para PDF'
                    },
                    'print',

                ],

            }
        )
    });
</script>
</body>
</html>
