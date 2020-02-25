<html>
    <body>
        <a href="{{route('pictures.index')}}">retour</a>
        <h1>Créer :)</h1>
        <form action="{{route('pictures.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="text" name="title" required/>
            {{$errors->first('title')}}<br/>
            <input type="file" name="picture" required/>
            {{$errors->first('picture')}}<br/>
            <button type="submit">Envoyer</button>
        </form>
    </body>
</html>
