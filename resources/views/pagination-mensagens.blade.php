@if(isset($mensagens))
    <?php $dia = ''; ?>
    @foreach(array_reverse($mensagens) as $mensagem)
        @if(diaSemana($mensagem->created_at) != $dia)
            <?php $dia = diaSemana($mensagem->created_at); ?>

            <div class="row dia">
                <h3>{{ $dia }}</h3>
            </div>
        @endif

        <div class="row">
            <div class="msg {{ $mensagem->remetente_id == Auth::guard('web')->user()->id ? 'enviada' : 'recebida' }}">
                <p>{{ $mensagem->mensagem }}</p>

                <span>{{ date('H:i', strtotime($mensagem->created_at)) }}</span>
            </div>
        </div>
    @endforeach
@endif
