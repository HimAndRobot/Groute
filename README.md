
<h1>Documetação</h1>

<p> Uma rota no Groute é formada pelo conjunto de valores(Meto, Rota, Ação). Como observado abaixo.</p>

 $app->metodo('rota','ação')  <br>
<span> O valor metodo faz referencia a o tipo da requisição html. Sendo aceito requisições do tipo </span> <br>
[GET] <br>
[POST] <br>
[DELETE] <br>
[PUT] <br>
<p>Ex: a rota abaixo chama o controller home e a action index se receber uma requisição get
$route->get('/home/{$gean}/teste','home@index');</p>

<p>Ex: já esse outro com a mesma rota chama outro controller se receber a requisição post
$route->post('/home/{$gean}/teste','homa@registro');</p>



obs2: todos os get sao automaticamente passados;

JÁ FEITO -------------------------------------------------------------------------------------------

* 1.0 - ADICIONADO METODO GET
* 1.1 - ADICIONADO METODO POST 50% FUNCINAL
* 1.5 - CORREÇÕES OTIMIZAÇÕES, BLOQUEIO DO INDEX.PHP, ADICÃO DA ROTA '/'
* 2.0 - ADICIONADO SISTEMA DE GRUPO E MIDDLEWARE 70% FUNCIONAL * MELHORAR NAS PROXIMAS VERSÕES
* 2.1 - 01/01 ADICIONADO SISTEMA DE PAGINAÇÃO  COM A ADICAÇÃO DE FUNÕES DE APOIO LIMIT E PAGNUMBER
* 2.2 - 02/01 - ADICIONADO A OPÇÃO DE PARAMETRO  OPCIONAL NA ROTA, AINDA TRABALHANDO NOS MIDDLEWARE, SEM MELHORAS
* 2.3 - 02/01 - SISTEMA DE MIDDLEWARE 100% FUNCIONAL GRUPO 50% CORRIGIR PORXIMA ATUALIZAÇÃO
* 3.0 - Mudaça em todo sistema de rota toda classe foi refeita, agora esta funcionando 100% com 
        diferenciação de rotas iguais e parametros diferente e a adiçao de paramentros na rota index,
        metood POST 100% funcional adicionado metodos(DELETE,PUT,OPTIONS,PATCH)
* 3.1 - Adicionado mapas de rotas        

FAZENDO HOJE ---------------------------------------------------------------------------------------
* 1.0 - TERMINA MIDDLEWARE ************ feito;
* 1.1 - ADICIONAR OPÇÃO SETNOME NAS ROTAS
* 1.2 


PACK NOTES FUTUROS ---------------------------------------------------------------------------------
* 2.5 - ADICIOANAR FILTROS AOS PARAMETROS NUMERICOS E DE STRINGS.
* 2.6 - ATUALIZAR O METODO POST PARA 100% ***** FEITO
* 3.0 - TRANSFORMA TODO SISTEMA DE ROTAS EM ESTATICAS ******CANCELADO IDEIA IDIOTA
* 3.1 - MELHORAR O SITEMAS DE GRUPOS ************* FEITO
