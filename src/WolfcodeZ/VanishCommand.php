<?php

namespace WolfcodeZ;

use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use WolfcodeZ\Main;

class VanishCommand extends Command{

    private $main;

    public function __construct(Main $main) {
        parent::__construct("vanish");
        $this->setDescription("Vanish Command");
        $this->main = $main;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
            if ($sender instanceof Player) {
                if (empty($args[0])) {
                    $name = $sender->getName();
                    $player = $sender->getPlayer();
                    if (!in_array($name, $this->main->vanish)) {
                        $this->main->vanish[] = $name;
                        $sender->sendMessage('§7[§eCore§7]§a You are now invisible');
                        $sender->setAllowFlight(true);
                        $sender->sendMessage("§7[§eCore§7]§a You can now fly");
                        Server::getInstance()->removePlayerListData($sender->getUniqueId());
                    } else {
                        $sender->sendMessage('§7[§eCore§7]§c You are no longer in Vanish mode');
                        $sender->setAllowFlight(false);
                        $sender->setFlying(false);
                        $sender->sendMessage("§7[§eCore§7]§c You can no longer fly");
                        Server::getInstance()->updatePlayerListData($sender->getUniqueId(), $sender->getId(), $sender->getDisplayName(), $sender->getSkin(), $sender->getXuid());
                        unset($this->main->vanish[array_search($name, $this->main->vanish)]);
                        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                            $player->showPlayer($sender);
                        }
                    }
                }
            }
        }

}
