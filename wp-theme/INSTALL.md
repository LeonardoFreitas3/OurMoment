# OurMoment — Guia de Instalação WordPress

## 1. Hosting + WordPress

1. Compra hosting WordPress (Hostinger, Namecheap, etc.)
2. Regista o domínio
3. Instala WordPress (1-click no painel do hosting)

## 2. Instalar Tema Pai (Astra)

1. WordPress Admin → Aparência → Temas → Adicionar Novo
2. Pesquisa "Astra" → Instalar → Ativar

## 3. Instalar Child Theme (OurMoment)

1. Comprime a pasta `ourmoment/` como ZIP (ourmoment.zip)
2. WordPress Admin → Aparência → Temas → Adicionar Novo → Carregar Tema
3. Faz upload do `ourmoment.zip` → Instalar → Ativar

## 4. Instalar Plugins

WordPress Admin → Plugins → Adicionar Novo:

- **WooCommerce** — pesquisa e instala
- **Printify for WooCommerce** — pesquisa e instala
- **Contact Form 7** (opcional, para o formulário de contacto)

## 5. Configurar Menu

1. Aparência → Menus
2. Cria menu "Primary Menu"
3. Adiciona links personalizados:
   - Shop → #shop (ou link para página Shop do WooCommerce)
   - About → #about
   - Contact → #contact
4. Define como "Primary Menu" na localização

## 6. Configurar Página Inicial

1. Definições → Leitura
2. Seleciona "Uma página estática"
3. Página inicial: cria uma página chamada "Home"
4. O tema usa automaticamente `front-page.php`

## 7. Ligar Printify

1. Cria conta em printify.com (plano gratuito)
2. Desenha os produtos no editor do Printify
3. No plugin Printify no WordPress: liga a tua conta
4. Sincroniza os produtos → aparecem no WooCommerce

## 8. Pagamentos

1. WooCommerce → Definições → Pagamentos
2. Ativa Stripe ou WooCommerce Payments
3. Configura a tua conta

## Estrutura do Tema

```
ourmoment/
├── style.css              ← estilos da marca + WooCommerce
├── functions.php          ← configuração, shortcodes, logo SVG
├── front-page.php         ← homepage custom
├── template-parts/
│   ├── hero.php           ← secção hero
│   ├── about.php          ← secção about
│   └── contact.php        ← secção contact
└── assets/
    ├── js/main.js         ← scroll effects, fade-in
    └── img/               ← imagens (logo, etc.)
```
