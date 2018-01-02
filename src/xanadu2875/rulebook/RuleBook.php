<?php

namespace xanadu2875\rulebook;

use pocketmine\plugin\PluginBase;
use pocketmine\event;
use pocketmine\item;
use pocketmine\utils\Config;
use pocketmine\utils\Utils;
use pocketmine\network\mcpe\protocol\PhotoTransferPacket;
use pocketmine\nbt\tag\LongTag;

class RuleBook extends PluginBase implements event\Listener
{
  private $db;
  private $book;
  private $server;
  private $photo = [];

  public function onLoad()
  {
    $this->server = $this;

    @mkdir($this->getDataFolder(), 777);

    if(!$this->checkUpdata())
    {
      @$this->getLogger()->notice("新しいバージョンがリリースされています Check!! ⇒ https://forum.pmmp.ga/d/42-preteleporter");
    }

    try{
      $this->db = new \SQLite3($this->getDataFolder() . "player.db");
    }
    catch(Exception $e)
    {
      $this->getServer()->getLogger()->critical("Couldn't connect to SQLite3 database: " . $this->db->connect_error);
    }
    try {
      $this->db->query("CREATE TABLE IF NOT EXISTS joindata(
        user_name TEXT PRIMARY KEY
      )");
    } catch (\Exception $e) {
      $this->getServer()->getLogger()->critical("Couldn't create table: " . $this->db->error);
    }

    $this->makeBook((new Config($this->getDataFolder() . "RuleBook.yml", Config::YAML,
    [
      "title" => "ルールブック",
      "author" => "オーナー",
      "page" => [
        [
          'type' => 'text',
          'data' => 'ルール'
        ],
        [
          'type' => 'text',
          'data' => 'あけおめ'
        ]
      ]
    ]))->getAll());
  }

  public function onEnable() { $this->getServer()->getPluginManager()->registerEvents($this, $this); }

  private function checkUpdata(): bool
  {
    $res = str_replace('\n', "", Utils::getURL("https://raw.githubusercontent.com/Xanadu2875/VersionManager/master/RuleBook"));
    return $res === $this->getDescription()->getVersion() ? false : true;
  }

  private function makeBook($data): void
  {
    $book = item\ItemFactory::get(item\Item::WRITTEN_BOOK, 0, 1);
    $book->setTitle($data["title"]);
    $book->setAuthor($data["author"]);
    $i = 0;
    while(isset($data["page"][$i]))
    {
      $book->addPage($i);

      if(($type = $data["page"][$i]["type"]) == 'text')
      {
        $book->setPageText($i, $data["page"][$i]["data"]);
      }
      elseif($type == 'image')
      {
        $photo = new PhotoTransferPacket;
        $photo->photoName = (string)$i . ".png";
        if($res = @file_get_contents($data["page"][$i]["data"]))
        {
          $photo->photoData = $res;
        }
        $photo->bookId = "2875";
        $this->photo[] = $photo;
        $tag = $book->getNamedTag();
        $tag->id = new LongTag('id', 2875);
        $tag->pages->{$i}->photoName->setValue((string)$i . ".png");
        $book->setNamedTag($tag);
      }
      $i++;
    }
    $this->book = $book;
  }

  public function onJoin(event\player\PlayerJoinEvent $event)
  {
    $player = $event->getPlayer();
    $name = strtolower($player->getName());
    if(!($this->db->query("SELECT * FROM joindata WHERE user_name='" . $this->db->escapeString($name) . "'")->fetchArray()))
    {
      $this->db->query("INSERT INTO joindata (user_name) VALUES ('" . $this->db->escapeString($name) . "')");
      $player->getInventory()->addItem($this->book);
    }
  }
}
