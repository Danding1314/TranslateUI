<?php

namespace Translate;

###
use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent};
###
use pocketmine\command\{Command, CommandExecutor, CommandSender};
###
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
###
use pocketmine\level\{Position, Level};
use pocketmine\utils\{TextFormat, Config};
###
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\Server;
use pocketmine\level\sound\AnvilFallSound;
###
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
###
use pocketmine\event\player\{PlayerRespawnEvent, PlayerLoginEvent, PlayerQuitEvent, PlayerInteractEvent, PlayerJoinEvent};
###
use pocketmine\network\mcpe\protocol\{LevelEventPacket, LevelSoundEventPacket, BlockEventPacket};
###
use pocketmine\Player;
use pocketmine\math\AxisAlignedBB;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
###
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\math\Math;
###
use phuongaz\Translate\Translate;   
use Translate\jojoe77777\FormAPI\{CustomForm, SimpleForm, FormAPI, ModalForm, Form};


class Main extends PluginBase implements Listener{

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("Translate By SD");
    }

    public function UI(Player $player){
        $form = new CustomForm(function (Player $player, $data) {
        $sou = $data[0];
        $tar = $data[1];
        $ret = $data[2];
    
        if($ret && $sou && $tar !== null){
        $one = Translate::detect($sou);//去偵測是啥語言ex Big 會自動偵測成en
        $oh = Translate::translate($one, $tar, $ret);
        $player->sendMessage("§c[翻譯機] §7> §f".$oh."");
        }
        });
        $form->setTitle("§f| 翻譯機 |");
        $form->addInput("§f輸入文字(會自動偵測語言)");
        $form->addInput("§d轉換的語言(中文繁體:zh-TW)");
        $form->addInput("§f想轉換的話(文字需跟第一行一樣)");
        $form->sendToPlayer($player);
        return true;
        }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool
    {
        switch ($cmd->getName()) {
        case "translate":
        $this->UI($sender);
        return true;
    }
    return true;
}
    //
    public function onDisable(){
    //ByeBYe
    }
}
