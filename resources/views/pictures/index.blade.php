<a href="{{route('pictures.create')}}"><b>Créer</b></a><br/>
@foreach ($pictures as $pic)
    <a href="{{route('pictures.show', $pic->id)}}">{{$pic->title}}</a>
    <br/>
@endforeach