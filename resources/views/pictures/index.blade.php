<a href="{{route('pictures.create')}}"><b>Créer</b></a><br/>
@foreach ($pictures as $pic)
    <a href="{{route('pictures.show', $pic->id)}}">
        <div style="display:inline-block; margin:5px; box-shadow: 0 0 5px #666;">
            <h3>{{$pic->title}}</h3>
            <img src="{{route('pictures.show', $pic->id)}}" style="height:200px"/>
        </div>
    </a>
@endforeach