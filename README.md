# 🎵 Music Backloggd

O **Music Backloggd** é uma aplicação web inspirada em plataformas como Letterboxd e Backloggd, voltada para amantes de música que desejam organizar, avaliar e comentar sobre suas faixas favoritas do Spotify. A plataforma permite que usuários conectem sua conta do Spotify, visualizem músicas ouvidas recentemente, avaliem faixas, comentem avaliações de outros usuários e até criem playlists com base nas músicas que já avaliaram.

---

## 🚀 Instruções de Execução

### Pré-requisitos

- PHP >= 8.1
- Composer
- Laravel >= 10
- MySQL ou MariaDB
- Node.js & npm (para assets)
- Conta de desenvolvedor no Spotify (para obter client ID e secret)

### 1. Clonar o repositório

```bash
git clone https://github.com/seu-usuario/music-backloggd.git
cd music-backloggd
```

### 2. Instalar dependências PHP e JS

```bash
composer install
npm install && npm run dev
```

### 3. Configurar ambiente

```bash
cp .env.example .env
php artisan key:generate
```

### Edite o arquivo .env com suas credenciais do banco de dados e do Spotify:

```bash
DB_DATABASE=music_backlog
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

SPOTIFY_CLIENT_ID=xxx
SPOTIFY_CLIENT_SECRET=xxx
SPOTIFY_REDIRECT_URI=http://localhost:8000/auth/callback
```

### 4. Rodar migrações e seeders

```bash
php artisan migrate --seed
```

### 5. Subir o servidor

```bash
php artisan serve
```

### 🧪 Funcionalidades principais (Casos de uso)
✅ Autenticação via Spotify (OAuth)

✅ Visualização de faixas recentemente ouvidas

✅ Avaliação de músicas (comentário + nota)

✅ Comentários em avaliações de outros usuários

✅ Criação de playlists com faixas avaliadas

✅ Perfil público com histórico de avaliações

### ⚙️ Tecnologias Utilizadas
Laravel 10.x – Framework backend

Laravel Socialite – Integração com OAuth do Spotify

Tailwind CSS – Estilização moderna e responsiva

Spotify Web API – Dados das faixas, criação de playlists

MySQL – Banco de dados relacional

Blade – Template engine do Laravel

Vite – Build rápido dos assets

Faker – Geração de dados fake para seeders

### 🗂 Organização dos Casos de Uso e Modelos
Principais Modelos
Modelo	Descrição
User	Representa o usuário autenticado via Spotify. Armazena tokens e avatar.
Review	Avaliações de músicas com nota e comentário.
ReviewComment	Comentários nas avaliações de outros usuários.

### Fluxos de uso
Usuário conecta com Spotify

Captura e armazena tokens (access, refresh, expires)

Atualiza perfil com dados do Spotify

Avaliação de música

Usuário acessa uma música (via ID)

Avalia (1–5 estrelas) e comenta

Avaliações ficam públicas no perfil

Comentar uma avaliação

Usuários podem comentar avaliações de outros usuários

O autor da avaliação não pode comentar a si mesmo

Criar playlist com avaliadas

Se a playlist Minhas Avaliações não existir, ela é criada

As faixas avaliadas (e ainda não adicionadas) são inseridas

Feedback em tela é exibido

###  📁 Estrutura de Diretórios Importantes
```bash
app/
├── Models/
│   ├── User.php
│   ├── Review.php
│   └── ReviewComment.php
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── ReviewController.php
│   │   └── PlaylistController.php
resources/
└── views/
    ├── dashboard.blade.php
    ├── track/show.blade.php
    ├── review/form.blade.php
    └── user/profile.blade.php
```
