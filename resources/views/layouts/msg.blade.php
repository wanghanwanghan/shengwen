@foreach (['danger','warning','success','info'] as $msg)
	@if(session()->has($msg))
		<div class="flash-message">
			<p style="text-align: center" class="alert alert-{{ $msg }}">
				{{ session()->get($msg) }}
			</p>
		</div>
	@endif
@endforeach