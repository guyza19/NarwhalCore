<?php

namespace Narwhal;

use pocketmine\plugin\PluginBase;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;

//use pocketmine\server;
use pocketmine\Player;
use pocketmine\item\Item;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerInteractEvent;
//use pocketmine\level\Position;

use pocketmine\utils\TextFormat as c;
//use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{
	
	public $prefix = (c::AQUA . "[" . c::YELLOW . "NarwhalPE" . c::AQUA . "] ");
	public $noperm = (c::RED . "You don't have permission to use this command.");

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info(c::GREEN .  "enabled!");
		foreach($this->getServer()->getLevels() as $level){
			$level->setTime("6500");
			$level->stopTime();
		}
		//TODO: Config for protected worlds.
	}
	public function onDisable(){
		$this->getLogger()->info(c::RED . "disabled!");
	}

    /**
     * @param CommandSender $sender
     * @param Command $command
     * @param string $label
     * @param array $args
     * @return bool
     */
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
    switch(strtolower($command->getName())){
        case "smsg":
            if(isset($args[0]) && isset($args[1])) {
                $player = $this->getServer()->getPlayer($args[0]);
                $message = array_splice($args, 1, 99999);
                $message_send = implode(" ", $message);
                if($player) {
                    $player->sendMessage($message_send);
                } else {
                    $sender->sendMessage("§cThat player is not online!");
                }
            } else {
                    $sender->sendMessage("§cUsage: /smsg <player> <message>");
            }
        break;
        case "info":
            	if($sender instanceof Player){
					$sender->addTitle("§fYou're playing on §cN§6a§er§aw§bh§da§5l§fPE", "§7Have fun! (even though we don't have anything more than Plots xD", -1, 5*20, 30);
            	} else {
            		$sender->sendMessage(c::RED . "Run this command in game " . c::YELLOW . "as a title can't be sent to console xD");
            	}
        break;
        case "items":
        	if($sender instanceof Player){
        		$sender->addTitle("§bHere you go." , "§7(giving you the main items real quick)", 10, 3*20, 8);
        		$this->MainItems($sender);
        	}else{
        		$sender->sendMessage(c::RED."Run this command in game!");
        	}
        break;
    }
    return true;
}

    /**
     * @param PlayerJoinEvent $e
     */
    public function onJoin(PlayerJoinEvent $e){
		//Define stuff.
		$player = $e->getPlayer();
		$defaultlevel = $this->getServer()->getDefaultLevel();
		$defsppos = $defaultlevel->getSafeSpawn();
		
		//Action.
		$player->setLevel($defaultlevel);
		$player->teleport($defsppos);
		$this->MainItems($player);
		
		//Title.
		$task = new Task($this, $player);
		$this->getServer()->getScheduler()->scheduleDelayedTask($task, 75);
		$this->getServer()->getLogger()->info("PlayerJoinEvent has been called for player: " . $player->getName());
	}

    /**
     * @param Player $player
     */
    public function MainItems(Player $player){
	    $player->getInventory()->clearAll();
		$player->getInventory()->setItem(0, Item::get(357, 0, 1)->setLore(array("Shows info about the server that you're playing on."))->setCustomName("§a§lInfo"));
		$player->getInventory()->setItem(1, Item::get(345, 0, 1)->setLore(array ("Opens up the teleportation menu."))->setCustomName("§b§lTeleport"));
		$player->getInventory()->setItem(8, Item::get(378, 0, 1)->setLore(array ("Literally sends you a random item with the most random name."))->setCustomName("§e§lSend me something random!"));
		$player->getInventory()->setItem(2, Item::get(0, 0, 1)->setCustomName("You're on level ". $player->getLevel()->getName()));
	}

    /**
     * @param Player $p
     */
    public function TeleportItems(Player $p){
	    $i = $p->getInventory();
	    $i->clearAll();
	    $i->setItem(0, Item::get(381, 0, 1)->setLore(array ("Go back to the lobby."))->setCustomName("§aH§6u§eb"));
	    $i->setItem(1, Item::get(81, 0, 1)->setLore(array("Claim your own plot and show off your building skills!"))->setCustomName("§6Plots"));
	    $i->setItem(3, Item::get(261, 0, 1)->setLore(array ("§o§7(coming soon!)"))->setCustomName("???"));
	    $i->setItem(4, Item::get(263, 0, 1)->setLore(array ("§o§7(coming one day, not really soon.) :P"))->setCustomName("???"));
	    $i->setItem(8, Item::get(35, 14, 1)->setCustomName("§cBack"));
    }

    /**
     * @param PlayerInteractEvent $e
     */
    public function onInteract(PlayerInteractEvent $e){
		$p = $e->getPlayer();
		$item = $p->getInventory()->getItemInHand()->getName();
		switch($item){
            case "§a§lInfo":
                $p->addTitle("§fYou're on §cN§6a§er§aw§bh§da§5l§fPE", "§7Have fun!", -1, 5*20, 30);
                break;
            case "§b§lTeleport":
                $this->TeleportItems($p);
                break;
            case "§e§lSend me something random!":
                $ids = array (334, 335, 336, 337, 338, 339, 340, 341, 342, 343, 344, 345, 346, 347, 348, 349, 350, 351, 352, 353, 354, 355, 356, 357, 358, 359, 360, 361, 362, 363, 364, 365, 366, 367, 368, 369, 370, 371, 372, 373, 374, 375, 376, 377, 378, 379, 380, 381, 382, 383, 384, 385, 386, 387, 388, 389, 390, 391, 392, 393, 394, 395, 396, 397, 398, 399, 400, 444); //Ah.
                $randomid = array_rand($ids, 1);
                $nums = array (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16);
                $randomcount = array_rand($nums, 1);
                $names = array("Apple" , "Carrot", "NARWHAL", "Unicorn horn", "Star", "Mario's Hat", "Squirrel", "Squid", "Patrick", "You literally got the worst name ever", "I love you", "Ghast Tear", "Paperclip", "Roll of Stickers", "Coffee Mug", "Chenille Stick", "A bottle of perfume", "Tennis Ball", "Sponge", "Basketball", "Sponge", "Heart", "Duct Tape", "Astronaut Helmet", "Slime", "Thumbs Up", "I'm tired of writing random names", "If you're reading this it's too late", "Knife", "LAVA"); //I really struggled to find random item names so I used http://www.springhole.net/writing_roleplaying_randomators/randomobjects.htm
                $randomname = array_rand($names, 1);

                $p->addTitle("§bComing up!", "§7§oYou randomly got ".$randomcount." ".$names[$randomname]."(s).");
                $p->getInventory()->addItem(Item::get($randomid, 0, $randomcount)->setCustomName($names[$randomname]));
                break;
            case "§aH§6u§eb":
                $p->addTitle("§bTeleported you to the lobby.");
                $p->teleport($this->getServer()->getLevelByName("Hub")->getSafeSpawn());
                $this->MainItems($p);
                break;
            case "§cBack":
                $this->MainItems($p);
                break;
            case "§6Plots":
                $p->addTitle("§6Teleported you to Plots.");
                $p->teleport($this->getServer()->getLevelByName("Plots")->getSafeSpawn());
                break;
        }
	}

    /**
     * @param PlayerDropItemEvent $e
     */
    public function onDropItem(PlayerDropItemEvent $e){
		$p = $e->getPlayer();
		$lvlname = $p->getLevel()->getName();
		if(stripos("SW", $lvlname) === false){
			$e->setCancelled();
			$p->sendMessage(c::YELLOW . "You ".c::RED."can't ".c::YELLOW."drop items or blocks on this world.");
		}
	}

    /**
     * @param PlayerRespawnEvent $e
     */
    public function onRespawn(PlayerRespawnEvent $e){
		$p = $e->getPlayer();
		$this->MainItems($p);
	}
}