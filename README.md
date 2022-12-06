
<h1>Documentação</h1>

<p> Uma rota no Groute é formada pelo conjunto de valores(Meto, Rota, Ação). Como observado abaixo.</p>

 $app->metodo('rota','ação')  <br>
<span> O valor metodo faz referencia a o tipo da requisição html. Sendo aceito requisições do tipo </span> <br>
[GET] <br>
[POST] <br>
[DELETE] <br>
[PUT] <br>
<p>Ex: a rota abaixo chama o controller home e a action index se receber uma requisição get
$route->get('/home/{$gean}/teste','home@index');</p>

<p>Ex: já essa outra com a mesma rota chama outro controller se receber a requisição post
$route->post('/home/{$gean}/teste','homa@registro');</p>
