<?php
function _setCidade($cidade, $force = false)
{    
    if($cidade != null){
    	$cidade->load('estado');

        if($force == true or !Cookie::get('sessao_cidade_slug')) {
            Cookie::queue('sessao_cidade_id', $cidade->id, '525600');
            Cookie::queue('sessao_cidade_title', $cidade->title, '525600');
            Cookie::queue('sessao_cidade_slug', $cidade->slug, '525600');

            Cookie::queue('sessao_estado_id', $cidade->estado->id, '525600');
            Cookie::queue('sessao_estado_title', $cidade->estado->title, '525600');
            Cookie::queue('sessao_estado_slug', $cidade->estado->slug, '525600');
            Cookie::queue('sessao_estado_letter', $cidade->estado->letter, '525600');
            Cookie::queue('sessao_estado_letter_lc', $cidade->estado->letter_lc, '525600');
        }

    	return;
    }
}
