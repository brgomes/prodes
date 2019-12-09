{!! Form::model($liga, ['route' => ['ligas.update', $liga->id], 'method' => 'put']) !!}
	<div class="modal fade" id="modalEditarLiga">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">
	            <div class="modal-header">
	            	<h5 class="modal-title">{{ __('content.editar-liga') }}</h5>
	                <button type="button" class="close" data-dismiss="modal">
	                	<span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <div class="modal-body">
					@include('ligas._form')
	            </div>
	            <div class="modal-footer">
	            	<button type="submit" class="btn btn-success">{{ __('content.salvar') }}</button>
	                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('content.cancelar') }}</button>
	            </div>
	        </div>
	    </div>
	</div>
{!! Form::close() !!}
