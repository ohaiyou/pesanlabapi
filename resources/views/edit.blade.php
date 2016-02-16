@foreach($data as $value)
<form action="{{URL::to('karyawan/update/'.$value->id)}}" method="post">




  <input type="text" name="id" value="{{$value->id}}">

  <input type="text" name="nama" value="{{$value->nama}}">
  <input type="submit" value="ok">
</form>
@endforeach
