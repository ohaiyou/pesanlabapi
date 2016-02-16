<a href="{{URL::to('karyawan/input')}}">input</a>
<br><br>
@foreach($data as $value)

{{$value->id}} |
{{$value->nama}}|
<a href="{{URL::to('karyawan/edit/'.$value->id)}}">edit</a>
<a href="{{URL::to('karyawan/destroy/'.$value->id)}}">delete</a><br>

  @endforeach
