<?php

namespace Narwhal;

use pocketmine\scheduler\PluginTask;
use Narwhal\Main;
use pocketmine\Player;
use pocketmine\utils\TextFormat as c;

class Task extends PluginTask{

public $player;
    /**
     * @var \Narwhal\Main
     */
    private $main;

    /**
     * Task constructor.
     * @param \Narwhal\Main $main
     * @param Player $player
     */
    public function __construct(Main $main, Player $player){
		parent::__construct($main);
		$this->player = $player;
        $this->main = $main;
    }

    /**
     * @param int $tick
     */
    public function onRun(int $tick){
	$player = $this->player;
	$pname = $player->getName();
	$player->addTitle("§cN§6a§er§aw§bh§da§5l§fPE", "§7Welcome, §a" . $pname . "§7!", 30, 20*20, 30);
	//$this->getOwner()->getServer()->getLogger()->info("Title should be sent to ".c::GREEN. $pname); //use this for checking
	}
}