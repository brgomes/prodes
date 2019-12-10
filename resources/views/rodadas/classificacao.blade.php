<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        	<h5 class="modal-title">{{ __('content.classificacao-da-rodada') }}</h5>
            <button type="button" class="close" data-dismiss="modal">
            	<span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
			<table class="table table-striped">
				<thead>
					<tr>
						<th></th>
						<th>{{ __('content.jogador') }}</th>
						<th class="text-center">{{ __('content.sigla-pontos') }}</th>
						<th class="text-center">{{ __('content.sigla-aproveitamento') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($rodada->classificacao as $item)
						@if ($item->jogador_id == $jogador->id)
							<tr class="table-success">
						@else
							<tr>
						@endif

							<td>@if ($item->posicao) {{ $item->posicao }}º @else - @endif</td>
							<td>
								<span class="{{ $item->jogador->usuario->bandeira }}" title="{{ $item->jogador->usuario->pais->nome }}"></span>
								{{ $item->jogador->usuario->primeironome . ' ' . $item->jogador->usuario->sobrenome }}

								@if ($item->jogador->admin)
									<span class="fas fa-star text-warning" title="Admin"></span>
								@endif
							</td>
							<td class="text-center">{{ $item->pontosganhos }}</td>
							<td class="text-center">@if ($item->aproveitamento) {{ $item->aproveitamentof . '%' }} @else - @endif</td>
						</tr>
					@endforeach
				</tbody>
			</table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('content.fechar') }}</button>
        </div>
    </div>
</div>
