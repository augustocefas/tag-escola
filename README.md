art install:api
art config:publish cors
art storage:link



Crie o controller baseado neste model, em app/http/controllers/client
estou montando um projeto para funcionar na escola onde os pais vao utilizar um chaveiro bluethooth e ao chegarem na porta da escola o chavei vai se conectar a uma gatway e vai notificar a sala que o pai/responsavel do aluno chegou, permitindo assim que o aluno possa sair da sala e se direcionar ate o portao.
Com base nessa infomacao tenho uma backend em laravel usando a base de dados que te passei colada aqui.
Vou ter um front adminsitrativo em react que tbm vai ter a tela que vai mostrar os pais que chegaram para os professores liberarem os alunos.
Preciso da sua ajuda pra montar um socket que vai receber a informacao do gatway com o numero da tag, uma vez que a informacao desta tag chegar ele vai disparar em uma tela publica que o pai esta na escola e disparar tbm para a tela do professor,
A idea que e que o cliente nao fique consutando o servidor, o socket sempre envia a nova informacao para o cliente,
Com base nisto me ajude a montar o socket em typescript e node


FLUXO

Route::post('/tag/evento', [TagEventoController::class, 'receber']);

ESP32
│  POST /api/tag/evento  (Bearer GATEWAY_TOKEN)
▼
Laravel (TagEventoController)
│  valida token do gateway
│  POST /interno/tag-detectada  (INTERNAL_SECRET)
▼
Socket Server (Node.js)
│  consulta MySQL: mac → aluno → sala
│  emite evento via Socket.io
├──▶ room "sala:{id}"   → tela do professor
└──▶ room "public"      → tela pública


ENV LARAVEL

    SOCKET_SERVER_URL
    SOCKET_INTERNAL_SECRET
    GATEWAY_TOKEN

ENV GATEWAY
    
    GATEWAY_TOKEN

ENV SOCKET

    PORT
    INTERNAL_SECRET
    DB_HOST
    DB_PORT
    DB_USER
    DB_PASSWORD
    DB_NAME
    CORS_ORIGINS


# phone 45:22:94:74:10:ED
# chpad 18:CC:70:FA:D5:17
# ?pad 4E:F2:2F:2B:CD:AC
# ???? C8:40:CD:C3:1B:16
