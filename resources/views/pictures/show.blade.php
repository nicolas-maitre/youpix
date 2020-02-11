<a href="{{route('pictures.index')}}">retour</a>
<h1>{{$picture->title}}</h1>
<div style="background-image: url({{route('pictures.show', $picture->id)}}); width: 100%; height: 80vh; background-size: contain; background-position: center; background-repeat: no-repeat"></div>