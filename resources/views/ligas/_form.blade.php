<div class="form-group row">
	<label class="col-sm-3 col-form-label" for="nome">{{ __('content.nome') }}</label>
	<div class="col-sm-9">
		{!! Form::text('nome', null, ['class' => 'form-control', 'id' => 'nome', 'maxlength' => 50, 'required']) !!}
	</div>
</div>

<div class="form-group row">
	<label class="col-sm-3 col-form-label" for="datainicio">{{ __('content.data-inicial') }}</label>
	<div class="col-sm-9">
		{!! Form::date('datainicio', null, ['class' => 'form-control', 'id' => 'datainicio', 'required']) !!}
	</div>
</div>

<div class="form-group row">
	<label class="col-sm-3 col-form-label" for="datafim">{{ __('content.data-final') }}</label>
	<div class="col-sm-9">
		{!! Form::date('datafim', null, ['class' => 'form-control', 'id' => 'datafim', 'required']) !!}
	</div>
</div>

<div class="form-group row">
	<label class="col-sm-3 col-form-label" for="regulamento">{{ __('content.regulamento') }}</label>
	<div class="col-sm-9">
		{!! Form::textarea('regulamento', null, ['class' => 'form-control', 'id' => 'regulamento']) !!}
	</div>
</div>
