<?php

namespace App\Classes;

use App\Classes\Traits\PriceableTrait;
use App\Classes\Abstracts\AbstractItem;

class Menu extends AbstractItem {
    use PriceableTrait;

    const TYPE_DUO = 'duo';
    const TYPE_TRIO = 'trio';
    const TYPE_QUATRO = 'quatro';

    const FORMAT_SMALL = 'small';
    const FORMAT_LARGE = 'large';

    private $childMenu; 
    private $type; 
    private $format; 
    private $sortOrder; 

    public function __construct(
        $id,
        $name,
        $childMenu,
        $type,
        $format,
        $priceHT,
        $tva,
        $sortOrder = 0
    ) {
        parent::__construct($id, $name, $priceHT, $tva);
        $this->childMenu = $childMenu;
        $this->setType($type);
        $this->setFormat($format);
        $this->sortOrder = $sortOrder;
    }


    public function isChildMenu() {
        return $this->childMenu;
    }

    public function setChildMenu($childMenu) {
        $this->childMenu = $childMenu;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $validTypes = [self::TYPE_DUO, self::TYPE_TRIO, self::TYPE_QUATRO];
        if (!in_array($type, $validTypes)) {
            throw new \InvalidArgumentException("Type invalide : $type");
        }
        $this->type = $type;
    }

    public function getFormat() {
        return $this->format;
    }

    public function setFormat($format) {
        $validFormats = [self::FORMAT_SMALL, self::FORMAT_LARGE];
        if (!in_array($format, $validFormats)) {
            throw new \InvalidArgumentException("Format invalide : $format");
        }
        $this->format = $format;
    }

    public function getSortOrder() {
        return $this->sortOrder;
    }

    public function setSortOrder($sortOrder) {
        $this->sortOrder = $sortOrder;
    }
}
