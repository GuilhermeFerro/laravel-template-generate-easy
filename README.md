# laravel-template-generate-easy

Estudar esses videos:
- https://laracasts.com/series/guest-spotlight/episodes/9
- https://www.youtube.com/watch?v=2sPfQIC7cqk&list=PLmwAMIdrAmK5q0c0JUqzW3u9tb0AqW95w&ab_channel=JasonMcCreary

# Generate entidade
- para o stub funcionar, é preciso que o arquivo seja publicado na pasta /stubs
- registrar o comando em Kernel no publish

comando tem q fazer: (Pesquisas)

- crud
  php artisan make:model Entidade -m # ser executada via blueprint
  php artisan make:datatable Entidade/Table Entidade
  php artisan livewire:make Entidade/Index --test --stub=vendor/gsferro/template-generate-easy/src/stubs/livewire # com o stub modificado
  php artisan livewire:make Entidade/Create --test --stub=vendor/gsferro/template-generate-easy/src/stubs/livewire/create # com o stub modificado # todo unificar
  php artisan livewire:make Entidade/Edit --test --stub=vendor/gsferro/template-generate-easy/src/stubs/livewire/edit # todo unificar

// form
# todo escrever o form baseado no migrate

- modal
  php artisan livewire:make Entidade/Modal --test --stub=vendor/gsferro/template-generate-easy/src/stubs/livewire/modal

- escrever em web.php

- dashboard
# perguntar se quer criar
php artisan make:livewire Dashboard/Entidade  --test --stub=vendor/gsferro/template-generate-easy/src/stubs/livewire/dashboard (usando outro stub)

- test (pest e dusk)
  php artisan pest:test EntidadeModelTest --unit # test model # Todo criar stub
  php artisan dusk:make EntidadeDuskTest  # Todo criar stub

##############################
Analisar:
- no meu comando de criação do livewire, eu pego o arquivos gerados e coloco o nome da Model q eh a Entidade
- ver metodo \Livewire\Commands\ComponentParser@classContents
- da um preg_replace colocando a route (q sera criado), Model e tudo mais que puder ser generico ou se baseando em perguntas ao executar o meu comando 
 
 