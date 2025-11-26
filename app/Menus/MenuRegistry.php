<?php

namespace App\Menus;

class MenuRegistry
{
    /**
     * Structure du menu :
     * [
     *   'section-id' => [
     *       'label' => 'Infrastructure',
     *       'items' => [
     *          [
     *            'id'         => 'firewall',
     *            'label'      => 'Pare-feu',
     *            'route'      => 'mercator.firewall.index',
     *            'icon'       => 'shield',
     *            'permission' => 'firewall.view'
     *          ],
     *       ]
     *   ]
     * ]
     */
    protected array $sections = [];

    public function addSection(string $id, string $label): void
    {
        if (!isset($this->sections[$id])) {
            $this->sections[$id] = [
                'label' => $label,
                'items' => [],
            ];
        }
    }

    public function addItem(string $sectionId, array $item): void
    {
        if (!isset($this->sections[$sectionId])) {
            $this->addSection($sectionId, ucfirst($sectionId));
        }

        $this->sections[$sectionId]['items'][] = $item;
    }

    public function get(): array
    {
        return $this->sections;
    }

    public function getSection(string $sectionId): ?array
    {
        return $this->sections[$sectionId] ?? null;
    }
}
