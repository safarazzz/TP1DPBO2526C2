<?php

class Menu
{
    private $id;
    private $nama;
    private $fnb;
    private $rasa;
    private $price;
    private $gambar;

    // constructor kosong & berparameter sekaligus (pakai default value)
    public function __construct($id = 0, $nama = "", $fnb = "", $rasa = "", $price = 0, $gambar = "")
    {
        $this->id     = $id;
        $this->nama   = $nama;
        $this->fnb    = $fnb;
        $this->rasa   = $rasa;
        $this->price  = $price;
        $this->gambar = $gambar;
    }

    // getter & setter id
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    // getter & setter nama
    public function getNama() { return $this->nama; }
    public function setNama($nama) { $this->nama = $nama; }

    // getter & setter fnb (food/beverage)
    public function getFnb() { return $this->fnb; }
    public function setFnb($fnb) { $this->fnb = $fnb; }

    // getter & setter rasa
    public function getRasa() { return $this->rasa; }
    public function setRasa($rasa) { $this->rasa = $rasa; }

    // getter & setter price
    public function getPrice() { return $this->price; }
    public function setPrice($price) { $this->price = $price; }

    // getter & setter gambar (path lokal)
    public function getGambar() { return $this->gambar; }
    public function setGambar($gambar) { $this->gambar = $gambar; }
}