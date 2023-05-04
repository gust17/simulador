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
<form action="{{url('api/consulta')}}" method="post">
    @csrf
    <div class="form-group">
        <label for="">Prazo</label>
        <input type="number" name="prazo" id="">
    </div>
    <div class="form-group">
        <label for="">valor</label>
        <input type="text" name="valor" id="">
    </div>
    <div class="form-group">
        <button>Cadastrar</button>
    </div>
</form>

</body>
</html>
