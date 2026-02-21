<?php

namespace DynamikaWeb\Adaptive\View\Components;

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

    public function normalizeItems()
    {
        $newRoots = [];

        if (empty($this->items)) {
            return [];
        }

        foreach ($this->items as $oldRoot) {
            $itemsRoot = data_get($oldRoot, 'items', []);
            $label = data_get($oldRoot, 'encode', true) ? e($oldRoot['label']) : $oldRoot['label'];
            $slug = Str::slug(strip_tags($oldRoot['label']));
            $target = data_get($oldRoot, 'target', '_self');
            $url = data_get($oldRoot, 'url', 'javascript:;');
            $newSubs = [];

            if (!empty($oldRoot['content'])) {
                $url = 'javascript:;';
                $newSubs[] = [
                    'label' => $label,
                    'url' => $url,
                    'slug' => '_auto',
                    'content' => $oldRoot['content'],
                    'items' => []
                ];
            }

            foreach ($itemsRoot as $oldSub) {
                $subItems = data_get($oldSub, 'items', []);
                $subContent = data_get($oldSub, 'content');

                if (empty($subItems) && empty($subContent)) { 
                    if (empty($newSubs) || 
                       (isset($newSubs[count($newSubs)-1]['items']) && count($newSubs[count($newSubs)-1]['items']) >= $this->maxItems) || 
                       $newSubs[0]['slug'] !== '_auto') {
                        $newSubs[] = ['slug' => '_auto', 'url' => 'javascript:;', 'items' => []];
                    }
                    
                    $newSubs[count($newSubs)-1]['items'][] = [
                        'url' => data_get($oldSub, 'url', 'javascript:;'),
                        'target' => data_get($oldSub, 'target', '_self'),
                        'label' => data_get($oldSub, 'label', '???'),
                    ];
                    continue;
                }

                $newSubs[] = [
                    'slug' => Str::slug(data_get($oldSub, 'label', '_none')),
                    'url' => data_get($oldSub, 'url', 'javascript:;'),
                    'content' => $subContent,
                    'label' => data_get($oldSub, 'label'),
                    'items' => []
                ];

                foreach ($subItems as $item) {
                    if (count($newSubs[count($newSubs)-1]['items']) >= $this->maxItems) {
                        $newSubs[] = ['slug' => '_auto', 'url' => 'javascript:;', 'items' => []];
                    }
                    
                    $newSubs[count($newSubs)-1]['items'][] = [
                        'url' => data_get($item, 'url', 'javascript:;'),
                        'target' => data_get($item, 'target', '_self'),
                        'label' => data_get($item, 'label', '???')
                    ];
                }
            }

            $newRoots[] = [
                'label' => $label,
                'slug' => $slug,
                'target' => $target,
                'url' => $url,
                'items' => $newSubs
            ];
        }

        return $newRoots;
    }
}