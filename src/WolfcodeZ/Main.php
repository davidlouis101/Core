<?php

namespace WolfcodeZ;

use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use WolfcodeZ\VanishCommand;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;
use WolfcodeZ\VanishTask;

class Main extends PluginBase implements Listener {

    public $vanish = [];
    public $prefix = "§7[§eCore§7] ";

    public function onEnable()
    {
        $this->getServer()->loadLevel("fw");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("Plugin loaded!");
        $this->getServer()->getCommandMap()->register("vanish", new VanishCommand($this));
        $this->getScheduler()->scheduleRepeatingTask(new VanishTask($this), 20);
    }

    public function onDisable()
    {
        $this->getLogger()->info("Plugin disabled");
    }

    public function OnJoin (PlayerJoinEvent $joinEvent)
    {
        $name = $joinEvent->getPlayer()->getName();
        $joinEvent->setJoinMessage("§7[§a+§7]§a $name");
    }
    public function OnQuit (PlayerQuitEvent $quitEvent){
        $name = $quitEvent->getPlayer()->getName();
        $quitEvent->setQuitMessage("§7[§c-§7]§c $name");
    }
        public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
        {
            switch ($cmd->getName()) {
                case "heal":
                    if ($sender instanceof Player){
                        if ($sender->hasPermission("core.heal")){
                            $player = $sender->getPlayer();
                            $player->setHealth(20);
                            $player->sendMessage($this->prefix."§aYou have been successfully healed");
                            return true;
                        }
                        }
                        case "day":
                            if ($sender instanceof Player) {
                                if ($sender->hasPermission("core.time")) {
                                    $player = $sender->getPlayer();
                                    $name = $player->getName();
                                    $level = $player->getLevel()->getName();
                                    $player->getLevel()->setTime(0);
                                    $player->sendMessage("The time was set to 0 (day)");
                                    $this->getServer()->broadcastMessage($this->prefix . "§aThe time in the world $level was set to 0 (day) by $name");
                                    return true;
                                }
                            }
                case "night":
                    if ($sender instanceof Player) {
                        if ($sender->hasPermission("core.time")) {
                            $player = $sender->getPlayer();
                            $name = $player->getName();
                            $level = $player->getLevel()->getName();
                            $player->getLevel()->setTime(14000);
                            $player->sendMessage("The time was set to 14000 (night)");
                            $this->getServer()->broadcastMessage("§aThe time in the world $level was set to 14000 (night) by $name");
                            return true;
                        }
                            return true;
                        }
                case "fw":
                    if ($sender instanceof Player){
                        $player = $sender->getPlayer();
                        $level = $player->getLevel()->getName();
                        $server = $this->getServer();
                        $fw = $this->getServer()->getLevelByName("fw");
                        $player->teleport($fw->getSpawnLocation());
                        $player->sendMessage($this->prefix."§aYou have been successfully teleported to the FW");
                        if ($level === "fw"){
                            $default = $server->getDefaultLevel();
                            $player->teleport($default->getSpawnLocation());
                            $player->sendMessage($this->prefix."§aYou have been successfully teleported to the normal world");
                            return true;
                        }
                        return true;
                    }
                case "generatefw":
                    if ($sender instanceof Player) {
                        if ($sender->hasPermission("generate.fw")) {
                            $server = $this->getServer();
                            $player = $sender->getPlayer();
                            $server->generateLevel("fw", "0", "default");
                            $player->sendMessage($this->prefix . "§aGenerate...");
                            if ($server->isLevelGenerated("fw")) {
                                $this->getServer()->loadLevel("fw");
                                $fw = $this->getServer()->getLevelByName("fw");
                                $sender->sendMessage($this->prefix . "§aFinished.");
                                $sender->sendMessage($this->prefix . "§aTeleporting...");
                                $player->teleport($fw->getSpawnLocation());
                                $sender->sendMessage($this->prefix . "§aFinished.");
                                return true;
                            }
                            return true;
                        }
                        return true;
                    }
            }
        }
}