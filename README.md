<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**
 
## Instruções de Uso

### Pré-requisitos
- Docker e Docker Compose instalados.
- PHP 8.2 e Composer.

### Clonar o Repositório
Primeiro, clone o repositório do Git:

```bash
git clone https://github.com/claytonsan/projeto-kanastra.git
cd projeto-kanastra

Configuração
1. Configurar Variáveis de Ambiente
Renomeie o arquivo .env.example para .env e ajuste as configurações conforme necessário:


Copiar código
cp .env.example .env
2. Gerar Chave da Aplicação
Gere a chave da aplicação Laravel:


Copiar código
./vendor/bin/sail artisan key:generate
3. Subir o Ambiente com Docker
Use o Laravel Sail para subir o ambiente com Docker:


Copiar código
./vendor/bin/sail up -d
Isso irá iniciar os serviços definidos no docker-compose.yml, como o servidor web e o banco de dados.

4. Rodar Migrations e Seeders
Após os serviços estarem no ar, execute as migrations e seeders para preparar o banco de dados:


Copiar código
./vendor/bin/sail artisan migrate --seed
5. Executar Testes
Para rodar os testes, você pode executar o seguinte comando:


Copiar código
./vendor/bin/sail test
6. Encerrar os Contêineres
Quando quiser parar o ambiente Docker, use:


Copiar código
./vendor/bin/sail down
Permissões de Diretório
Para garantir que a aplicação funcione corretamente, você precisa configurar as permissões dos diretórios storage e bootstrap/cache. Essas pastas são essenciais para o funcionamento da aplicação, pois armazenam logs, cache e dados temporários.

Execute os seguintes comandos para configurar as permissões corretamente:


Copiar código
sudo chown -R $USER:$USER storage
sudo chown -R $USER:$USER bootstrap/cache
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache
Certifique-se de que o usuário que está executando o servidor web tenha acesso a esses diretórios para evitar problemas durante a execução da aplicação.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
