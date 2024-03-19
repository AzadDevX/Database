<?php

namespace Azad\Database\Exceptions;

function SimilarityName (string $name,array $data) {
    $NewData = array_filter($data,function ($names) use ($name) {
        return levenshtein($name,$names) <= 3;
    });
    return $NewData;
}

function MakePluginFile($file,$class) {
    $myfile = fopen($file, 'w') or die("Unable to open file!");
    $txt = "<?php\n";
    $txt .= "namespace MyProject\Plugins;\n";
    $txt .= "class ".$class." extends \Azad\Database\Magic\Plugin {\n";
    $txt .= "    public function test (\$test) {\n";
    $txt .= "       \$Table = self::Table(\"TABLE_NAME\");\n";
    $txt .= "    }\n";
    $txt .= "}\n";
    $txt .= "?>";
    fwrite($myfile, $txt);
    fclose($myfile);
}

function MakeNormalizerFile($file,$class) {
    $myfile = fopen($file, 'w') or die("Unable to open file!");
    $txt = "<?php\n";
    $txt .= "namespace MyProject\Normalizers;\n";
    $txt .= "class ".$class." extends \Azad\Database\Magic\Normalizer {\n";
    $txt .= "    public static function Normalization (\$data) {\n";
    $txt .= "       return \$data;\n";
    $txt .= "    }\n";
    $txt .= "}\n";
    $txt .= "?>";
    fwrite($myfile, $txt);
    fclose($myfile);
}

function MakeEncrypterFile($file,$class) {
    $myfile = fopen($file, 'w') or die("Unable to open file!");
    $txt = "<?php\n";
    $txt .= "namespace MyProject\Encrypters;\n";
    $txt .= "class ".$class." extends \Azad\Database\Magic\Encrypter {\n";
    $txt .= "    public static function Encrypt (\$data) {\n";
    $txt .= "       return \$data;\n";
    $txt .= "    }\n";
    $txt .= "    public static function Decrypt (\$data) {\n";
    $txt .= "       return \$data;\n";
    $txt .= "    }\n";
    $txt .= "}\n";
    $txt .= "?>";
    fwrite($myfile, $txt);
    fclose($myfile);
}

class Debug extends \Exception {
    public function __construct($method,$system_data,$coder_data) {
        $method = str_replace('Azad\\Database\\','',$method);
        if ($method == "Connection::LoadPlugin") {
            $Plugins = glob($system_data["directory"]."/Plugins/*.php");
            $Plugins = array_map(fn ($x) => str_replace([$system_data["directory"]."/Plugins/",'.php'],'',$x),$Plugins);
            $address_to_name = str_replace($system_data["project_name"]."\Plugins\\",'',$coder_data);
            $SimilarityName = SimilarityName ($address_to_name,$Plugins);
            if (count($SimilarityName) >= 1) {
                $this->message = "Most likely, the plugin name has not been typed correctly. You have defined the name '$address_to_name', but is it not these names you meant? ".implode(",",$SimilarityName);
            } else {
                $file = $system_data["directory"]."\Plugins\\".$coder_data.".php";
                $address_to_name = str_replace($system_data["project_name"]."\Plugins\\",'',$coder_data);
                $this->message = "It seems that the plugin you specified does not exist, this plugin has been created manually and correctly by the system, please check the ".$file." file.";
                MakePluginFile($file,$address_to_name);
            }
        } 
        if ($method == "Connection::PreparingGet" or $method == "Connection::PreparationValues") {
            $Encrypters = glob($system_data["directory"]."/Encrypters/*.php");
            $Encrypters = array_map(fn ($x) => str_replace([$system_data["directory"]."/Encrypters/",'.php'],'',$x),$Encrypters);
            $address_to_name = str_replace($system_data["project_name"]."\Encrypters\\",'',$coder_data);
            $SimilarityName = SimilarityName ($address_to_name,$Encrypters);
            if (count($SimilarityName) >= 1) {
                $this->message = "Most likely, the encrypter name has not been typed correctly. You have defined the name '$address_to_name', but is it not these names you meant? ".implode(",",$SimilarityName);
            } else {
                $file = $system_data["directory"]."\Encrypters\\".$coder_data.".php";
                $address_to_name = str_replace($system_data["project_name"]."\Encrypters\\",'',$coder_data);
                $this->message = "It seems that the normalizer you specified does not exist, this normalizer has been created manually and correctly by the system, please check the ".$file." file.";
                MakeEncrypterFile($file,$address_to_name);
            }
        }
    }
}