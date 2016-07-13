<?php
/*
 * error__string__text - сss стиль для вывода ошибок
*/

$site = array(
    'header' => "inc/header.phtml",
    'footer' => "inc/footer.phtml",
    'default' => "inc/pages/index.phtml",
    'pages' => array(
        'dance' => "inc/pages/dance.phtml", // ?action=dance
        'direction' => "inc/pages/direction.phtml", // ?action=direction
        'direction-vn' => "inc/pages/direction-vn.phtml", // ?action=direction-vn
        'price' => "inc/pages/price.phtml", // ?action=price
        'teachers' => "inc/pages/teachers.phtml",// ?action=teachers
        'teachers__mini' => "inc/pages/teachers__mini.phtml",// ?action=teachers__mini
        'open-class' => "inc/pages/open-class.phtml",// ?action=open-class
        'schedule' => "inc/pages/schedule.phtml",// ?action=schedule
        'contacts' => "inc/pages/contacts.phtml"// ?action=contacts

    )
);
///////////////////////////////////////

class MakeUPFramework{
    protected $_options = array(
        "auto_repair_project" => true,

    );


    function __construct($tree)
    {
        $this->_options['site_tree'] = $tree;
        $this->repairFolder("inc/");
        $this->repairFolder("inc/pages/");

        $this->validateFile($this->_options['site_tree']["header"]);
        $this->validateFile($this->_options['site_tree']["footer"]);
        $this->validateFile($this->_options['site_tree']["default"]);

        foreach ($this->_options['site_tree']["pages"] as $key => $value){
            $this->validateFile($value);
        }
    }

    public function run($data)
    {
        $site = $this->_options['site_tree'];
        if (file_exists($site["header"])) {
            include $site["header"];
        }
        else { echo "<div class=\"error__string__text\">Добавь файл ".$site["header"]."</div>"; }

        if (array_key_exists($data, $site["pages"])) {
            if (file_exists($site["pages"][$data])) {
                include $site["pages"][$data];
            }
            else {
                echo "<div class=\"error__string__text\"> Неправильная ссылка на документ проверь секцию pages ключ ".$data.". Возможно файл ".$site["pages"][$data]." отсутствует.</div>";
            }
        }
        else{
            if (file_exists($site["default"])) {
                include $site["default"];
            } else echo "<div class=\"error__string__text\">Добавь файл ".$site["default"]."</div>";
        }

        if (file_exists($site["footer"])) {
            include $site["footer"];
        } else echo "<div class=\"error__string__text\">Добавь файл ".$site["footer"]."</div>";
    }


    public function validateFile($file){
        if (preg_replace("/^(.*)\./", '', $file) == "phtml"){
            if (!file_exists($file)) {
                if ($this->_options["auto_repair_project"]) {
                    $text = "File: " . $file . "<br />";
                    $fp = fopen($file, "w");
                    fwrite($fp, $text);
                    fclose($fp);
                } else {
                    echo "Файл " . $file . " не существует";
                }
            }
        }
    }


    public function repairFolder($folder){
        if (!file_exists($folder)) {
            if ($this->_options["auto_repair_project"]){
                mkdir($folder, 0777);
            }
            else {
                echo "Папки ".$folder." не существует";
            }
        }
    }
}
$MUPF = new MakeUPFramework($site);
$MUPF->run($_GET["action"]);

