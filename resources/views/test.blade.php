@extends('app')
@section('content')
<div class="container">
	<ul class="list-group">
	@foreach($feeds as $feed)
	<li class="list-group-item">{{ $feed }}</li>
	@endforeach
	</ul>
</div>
@stop
