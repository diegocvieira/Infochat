<div class="topo-resultados">
    <div class="abas-resultados">
        <a href="#" data-type="resultado" class="active">RESULTADO</a>
        <a href="#" data-type="pessoal">PESSOAL</a>
        <a href="#" data-type="trabalho">TRABALHO</a>
    </div>

    @if(isset($filtro_ordem))
        {!! Form::select('ordem', $filtro_ordem, null, ['class' => 'selectpicker filtro-ordem', 'title' => 'filtrar por']) !!}
    @endif
</div>
