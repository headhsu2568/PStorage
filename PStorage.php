<?
class PStorage implements Countable {
    protected $strg;
    protected $strgFile;
    private $auto;

    public function __construct($strgFile="", $auto=null) {
        $this->strg = Array();

        /***
         * this.auto:
         *      0: diable all
         *      1: create a storage file when a PStorage is created
         *      2: save to the storage file when the PStorage is modified
         *      3: delete the storage file when PStorage is empty
         *      4: enable all (1+2+3)
         *      5: 1+2
         *      6: 1+3
         ***/
        $this->setAuto($auto);
        if(!empty($strgFile)) {
            $this->strgFile = $strgFile;
            if($this->auto === 1 || $this->auto === 4 || $this->auto === 5 || $this->auto === 6) {
                $fd = @fopen($this->strgFile, "x");
                if($fd) fclose($fd);
            }
            if(file_exists($this->strgFile)) $this->load();
        }
    }

    public function __destruct() {}

    protected function setStorageFile($strgFile) {
        $this->strgFile = $strgFile;
    }

    protected function setAuto($auto=null) {
        if(is_numeric($auto) && $auto >= 0 && $auto < 6) $this->auto = $auto;
        else $this->auto = 1;
    }

    public function destroy() {
        if(!empty($this->strgFile)) {
            $this->strg = Array();
            @unlink($this->strgFile);
        }
    }

    public function save() {
        if(!empty($this->strgFile)) {
            $fd = fopen($this->strgFile, "w");
            fwrite($fd, serialize($this->strg));
            fclose($fd);
        }
    }

    public function load() {
        if(!empty($this->strgFile) && file_exists($this->strgFile)) {
            $str = file_get_contents($this->strgFile);
            $this->strg = @unserialize($str);
        }
    }

    public function getItem($key) {
        $key = "_".$key;
        return isset($this->strg[$key])?$this->strg[$key]:null;
    }

    public function setItem($key, $value) {
        $key = "_".$key;
        if($this->auto === 2 || $this->auto === 4 || $this->auto === 5) {
           if(isset($this->strg[$key]) && (serialize($this->strg[$key]) === serialize($value))) return;
           $this->strg[$key] = $value;
           $this->save();
        }
        else $this->strg[$key] = $value;
    }

    public function removeItem($key) {
        $key = "_".$key;
        unset($this->strg[$key]);
        if($this->auto === 2 || $this->auto === 5) $this->save();
        else if($this->auto === 3 || $this->auto === 4 || $this->auto === 6) {
            if($this->count() === 0) $this->destroy();
        }
    }

    public function clear() {
        $this->strg = Array();
        if($this->auto === 2 || $this->auto === 5) $this->save();
        else if($this->auto === 3 || $this->auto === 4 || $this->auto === 6) $this->destroy();
    }

    /*** implementation of Countable interface ***/
    public function count() {
        return count($this->strg);
    }
}
?>
