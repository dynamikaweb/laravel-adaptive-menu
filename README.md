dynamikasolucoesweb/laravel-adaptive-menu
=========================
![php version](https://img.shields.io/packagist/php-v/dynamikasolucoesweb/laravel-adaptive-menu)
![pkg version](https://img.shields.io/packagist/v/dynamikasolucoesweb/laravel-adaptive-menu)
![license](https://img.shields.io/packagist/l/dynamikasolucoesweb/laravel-adaptive-menu)
![quality](https://img.shields.io/scrutinizer/quality/g/dynamikaweb/laravel-adaptive-menu)
![build](https://img.shields.io/scrutinizer/build/g/dynamikaweb/laravel-adaptive-menu)

O Laravel Adaptive Menu é um componente Blade que transforma arrays complexos em menus responsivos e auto-ajustáveis. Ele redistribui sub-itens automaticamente para manter o equilíbrio visual do layout.

Instalação
------------
A maneira preferida de instalar esta extensão é através do [composer] [composer](http://getcomposer.org/download/).

Ou corre

```SHELL
$ composer require dynamikasolucoesweb/laravel-adaptive-menu "*"
```

ou adicione

```JSON
"dynamikasolucoesweb/laravel-adaptive-menu": "*"
```

à seção `require` do seu arquivo `composer.json`.

Assets & Customização
------------
Por padrão, a biblioteca injeta automaticamente o CSS e JS necessários. 

Caso deseje customizar os estilos ou scripts, você pode publicá-los em sua pasta pública:

```SHELL
$ php artisan vendor:publish --tag=adaptive-menu-assets
```

## ⚠️ Requisito do Layout
Para que os estilos e scripts sejam injetados automaticamente, seu arquivo de layout base **precisa** conter as diretivas `@stack`:

```html
@stack('css')

@stack('scripts')
```

Usage
------------
Certifique-se de que seu layout principal possua as diretivas @stack('css') e @stack('scripts'). Basta chamar o componente e passar o seu array de itens. Também é possível informar a quantidade máxima de itens por menu através da variável max-items. O componente gerencia o cache e a normalização dos dados automaticamente.

```HTML
<x-adaptive-menu :items="$menuTree" :max-items="5" id="main-navigation" />
```

Estrutura do Array
------------
O componente aceita uma estrutura de árvore. Abaixo, um exemplo de como formatar os dados (seja via Model ou Array estático):

```PHP
$menuTree = [
    [
        'label' => 'Institucional',
        'url' => '/quem-somos',
        'target' => '_self',
        'items' => [
            [
                'label' => 'Nossa História',
                'url' => '/historia',
                'target' => '_self',
                'items' => []
            ],
            [
                'label' => 'Equipe',
                'url' => '/equipe',
                'target' => '_self',
                'items' => []
            ],
        ]
    ],
    [
        'label' => 'Serviços',
        'url' => '/#',
        'target' => '_self',
        'content' => '<p>Texto customizado ou HTML</p>', // Conteúdo opcional
        'items' => [
            [
                'label' => 'Desenvolvimento Web',
                'url' => '/dev',
                'target' => '_blank',
                'content' => '<p>Texto customizado ou HTML</p>', // Conteúdo opcional
                'items' => []
            ],
            [
                'label' => 'Design',
                'url' => '/design',
                'target' => '_self',
                'items' => []
            ],
        ]
    ]
];
```

## ⚡ Performance & Cache
O componente utiliza uma camada de cache inteligente que se adapta ao ambiente:
- **Production**: Cache automático de 24h. O cache é invalidado automaticamente se os itens do menu forem alterados ou se o arquivo da biblioteca for atualizado.
- **Development**: Se APP_DEBUG=true, o cache é ignorado para refletir mudanças instantâneas.

Features
------------
Auto-Correction: Quando um grupo de itens excede o limite definido em max-items, a biblioteca cria novos grupos (_auto) para evitar quebras de layout.

Smart Caching: Em ambientes de produção (APP_DEBUG=false), o componente gera um hash único e armazena o HTML renderizado no cache por 24 horas.

Slug Generation: Gera automaticamente slugs para os IDs dos menus a partir dos labels.

Flexible Content: Suporte nativo para campos de content (HTML) dentro dos sub-menus.

Authors
------------
Giordani da Silveira dos Santos - giordani@dynamika.com.br

--------------------------------------------------------------------------------------------------------------
[![dynamika soluções web](https://avatars.githubusercontent.com/dynamikasolucoesweb?size=12)](https://dynamika.com.br)
This project is under [BSD-3-Clause](https://opensource.org/licenses/BSD-3-Clause) license.