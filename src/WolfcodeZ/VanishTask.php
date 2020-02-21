<?php
namespace WolfcodeZ;

use WolfcodeZ\Main;
use pocketmine\scheduler\Task;

class VanishTask extends Task {

    private $plugin;
    public function __construct(Main $pl)
    {
        $this->plugin = $pl;
        $this->plugin->getScheduler()->scheduleRepeatingTask($this, 5);
    }

    public function onRun(int $currentTick)
    {
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            foreach ($this->plugin->vanish as $vp) {
                $play = $this->plugin->getServer()->getPlayer($vp);
                if (!$player->hasPermission('core.show.all')) {
                    $player->hidePlayer($play);
                }
            }

        }
    }
}
