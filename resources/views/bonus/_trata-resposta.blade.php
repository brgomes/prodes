@if ($pergunta->{'opcaocorreta' . $index . '_id'})
	<i class="fas fa-user"></i>
	@if ($resposta = $pergunta->resposta($jogador->id, $index))
		{{ $resposta->{'opcao' . $index}->opcao }}
	@else
		-
	@endif

	@if ($pergunta->consolidada)
		<br />

		@if ($resposta)
			@if ($resposta->{'opcao' . $index . '_id'} == $pergunta->{'opcaocorreta' . $index . '_id'})
				<i class="fas fa-check-circle text-success"></i>
			@else
				<i class="fas fa-times-circle text-danger"></i>
			@endif
		@else
			<i class="fas fa-times-circle text-danger"></i>
		@endif

		{{ $pergunta->{'opcaoCorreta' . $index}->opcao }}
		({{ __('content.pontos') }}: {{ $resposta->{'pontos' . $index} }})
	@elseif ($jogador->admin)
		<br />
		<i class="fas fa-check-circle"></i>
		{{ $pergunta->{'opcaoCorreta' . $index}->opcao }}
		({{ __('content.precisa-consolidar') }})
	@endif
@else
	@if ($pergunta->datalimiteresposta >= $hoje)
		@if ($resposta = $pergunta->resposta($jogador->id, $index))
			{{ Form::select('resposta' . $index . '_' . $pergunta->id, $pergunta->pluckOpcoes(), $resposta->{'opcao' . $index . '_id'}, ['class' => 'form-control']) }}
		@else
			{{ Form::select('resposta' . $index . '_' . $pergunta->id, $pergunta->pluckOpcoes(), null, ['class' => 'form-control']) }}
		@endif

		@if ($pergunta->{'pontos' . $index})
			<small class="form-text text-muted">
				{{ __('content.pontos')}}: {{ $pergunta->{'pontos' . $index} }}
			</small>
		@endif
	@else
		<i class="fas fa-user"></i>
		@if ($resposta = $pergunta->resposta($jogador->id, $index))
			{{ $resposta->{'opcao' . $index}->opcao }}
		@else
			-
		@endif
	@endif
@endif
