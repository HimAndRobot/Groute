
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

