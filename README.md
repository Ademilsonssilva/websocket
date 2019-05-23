## Para testar o funcionamento do websocket: 

### Abrir o terminal, navegar até a pasta do projeto e usar o comando 

    php bin/chat-server.php
	 
Depois, abrindo o navegador, apertando F12 e indo na aba *console*, rode o seguinte bloco de código em duas janelas diferentes: 
    
	 var conn = new WebSocket('ws://localhost:8080');
	 conn.onopen = function(e) {
		  console.log("Connection established!");
	 };

	 conn.onmessage = function(e) {
		  console.log(e.data);
	 };
	 
E então, para mandar mensagens de uma janela para outra, basta usar no console o comando: 

    conn.send('mensagem desejada');
