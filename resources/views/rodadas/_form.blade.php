<div class="form-group row">
	<label class="col-sm-3 col-form-label" for="numero">{{ __('content.numero') }}</label>
	<div class="col-sm-9">
		{!! Form::number('numero', null, ['class' => 'form-control', 'id' => 'numero', 'required']) !!}
	</div>
</div>
