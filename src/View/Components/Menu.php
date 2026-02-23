<?php

namespace DynamikaSolucoesWeb\Adaptive\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Menu extends Component
{
    public $items;
    public $maxItems;
    public $id;

    public function __construct($items = [], $maxItems = 5, $id = null)
    {
        $this->items = $items;
        $this->maxItems = $maxItems;
        $this->id = $id ?? 'menu-' . Str::random(8);
    }

    public function render()
    {
        $renderHtml = fn() => view('adaptive-menu::components.menu-forest', [
            'normalizedItems' => $this->normalizeItems(),
            'id' => $this->id
        ])->render();

        $content = config('app.debug')
            ? $renderHtml() 
            : Cache::remember(
                "menu-" . md5(json_encode($this->items) . getlastmod() . $this->id), 
                now()->addDay(), 
                $renderHtml
            );

        return view('adaptive-menu::components.menu-wrapper', [
            'content' => $content,
            'id' => $this->id
        ]);
    }

        /**
     * Normaliza os itens do menu com foco em legibilidade e redução de complexidade.
     * * @return array
     */
    public function normalizeItems(): array
    {
        if (empty($this->items)) {
            return [];
        }

        return array_map(fn($root) => $this->processRootItem($root), $this->items);
    }

    /**
     * Processa cada item de nível superior (Root).
     */
    private function processRootItem(array $root): array
    {
        $label = data_get($root, 'encode', true) ? e($root['label']) : $root['label'];
        $url = !empty($root['content']) ? 'javascript:;' : data_get($root, 'url', 'javascript:;');
        
        $newSubs = [];
        if (!empty($root['content'])) {
            $newSubs[] = $this->createAutoItem($label, $root['content']);
        }

        $itemsRoot = data_get($root, 'items', []);
        foreach ($itemsRoot as $subItem) {
            $newSubs = $this->processSubItems($newSubs, $subItem);
        }

        return [
            'label'  => $label,
            'slug'   => Str::slug(strip_tags($root['label'])),
            'target' => data_get($root, 'target', '_self'),
            'url'    => $url,
            'items'  => $newRootsItems ?? $newSubs
        ];
    }

    /**
     * Processa a lógica de sub-itens e agrupamento automático.
     */
    private function processSubItems(array $newSubs, array $oldSub): array
    {
        $subItems = data_get($oldSub, 'items', []);
        $subContent = data_get($oldSub, 'content');

        // Caso seja um item simples (sem filhos ou conteúdo), agrupa no _auto
        if (empty($subItems) && empty($subContent)) {
            return $this->pushToAutoGroup($newSubs, $oldSub);
        }

        // Adiciona o sub-menu atual
        $newSubs[] = [
            'slug'    => Str::slug(data_get($oldSub, 'label', '_none')),
            'url'     => data_get($oldSub, 'url', 'javascript:;'),
            'content' => $subContent,
            'label'   => data_get($oldSub, 'label'),
            'items'   => []
        ];

        // Processa os filhos deste sub-menu respeitando o limite maxItems
        foreach ($subItems as $child) {
            $newSubs = $this->pushToLastMenu($newSubs, $child);
        }

        return $newSubs;
    }

    /**
     * Helper para garantir que o item seja adicionado ao último grupo respeitando o limite.
     */
    private function pushToLastMenu(array $storage, array $item): array
    {
        $lastIndex = count($storage) - 1;

        if (count($storage[$lastIndex]['items']) >= $this->maxItems) {
            $storage[] = $this->createAutoItem();
            $lastIndex++;
        }

        $storage[$lastIndex]['items'][] = $this->formatSimpleItem($item);
        return $storage;
    }

    /**
     * Lógica específica para o agrupamento "_auto" de itens simples.
     */
    private function pushToAutoGroup(array $storage, array $item): array
    {
        $needsNewGroup = empty($storage) || 
                        (end($storage)['slug'] !== '_auto') || 
                        (count(end($storage)['items']) >= $this->maxItems);

        if ($needsNewGroup) {
            $storage[] = $this->createAutoItem();
        }

        $storage[count($storage) - 1]['items'][] = $this->formatSimpleItem($item);
        return $storage;
    }

    /**
     * Fábrica de itens padronizados.
     */
    private function createAutoItem(?string $label = null, ?string $content = null): array
    {
        return [
            'label'   => $label,
            'url'     => 'javascript:;',
            'slug'    => '_auto',
            'content' => $content,
            'items'   => []
        ];
    }

    /**
     * Formata um item final (link).
     */
    private function formatSimpleItem(array $item): array
    {
        return [
            'url'    => data_get($item, 'url', 'javascript:;'),
            'target' => data_get($item, 'target', '_self'),
            'label'  => data_get($item, 'label', '???'),
        ];
    }
}