<?
class PStorage implements Countable {
    protected $strg;
    protected $strgFile;
    private $auto;

    public function __construct($strgFile="", $auto=false) {
        $this->strg = Array();
        $this->auto = $auto;
        if(!empty($strgFile)) {
            $this->strgFile = $strgFile;
            if($this->auto) {
                $fd = @fopen($this->strgFile, "x");
                if($fd) fclose($fd);
            }
            if(file_exists($this->strgFile)) {
                $str = file_get_contents($this->strgFile);
                $this->strg = @unserialize($str);
            }
        }
    }

    public function __destruct() {}

    public function setStorageFile($strgFile) {
        $this->strgFile = $strgFile;
    }

    public function destroy() {
        $this->strg = Array();
        unlink($this->strgFile);
    }

    public function save() {
        $fd = fopen($this->strgFile, "w");
        fwrite($fd, serialize($this->strg));
        fclose($fd);
    }

    public function load() {
        $str = file_get_contents($this->strgFile);
        $this->strg = @unserialize($str);
    }

    public function getItem($key) {
        $key = "_".$key;
        return isset($this->strg[$key])?$this->strg[$key]:null;
    }

    public function setItem($key, $value) {
        $key = "_".$key;
        $this->strg[$key] = $value;
    }

    public function removeItem($key) {
        $key = "_".$key;
        unset($this->strg[$key]);
    }

    public function clear() {
        $this->strg = Array();
    }

    /*** implementation of Countable interface ***/
    public function count() {
        return count($this->strg);
    }
}
?>
