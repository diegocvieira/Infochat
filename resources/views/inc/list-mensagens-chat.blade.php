<?php $dia = ''; ?>
@foreach(array_reverse($chat->messages->all()) as $mensagem)
    @if(diaSemana($mensagem->created_at) != $dia)
        <?php $dia = diaSemana($mensagem->created_at); ?>

        <div class="row dia">
            <h3>{{ $dia }}</h3>
        </div>
    @endif

    <div class="row {{ $mensagem->user_id == Auth::guard('web')->user()->id ? 'enviada' : 'recebida' }}">
        <div class="msg">
            <p>{{ $mensagem->message }}</p>

            <span>{{ date('H:i', strtotime($mensagem->created_at)) }}</span>
        </div>
    </div>
@endforeach
