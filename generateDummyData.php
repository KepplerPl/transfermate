<?php

$dummyDataFolder = __DIR__ . "/dummy_data";
$seedDataFile = __DIR__ . "/authorsAndBooks.json";

for($i = 0; $i < rand(2, 5); $i++) {
    gen($dummyDataFolder, $seedDataFile);
}

function gen($dummyDataFolder, $seedDataFile) {
    $xml = <<<PHP
<?xml version="1.0"?>
<book>
    <author>__AUTHOR__</author>
    <name>__BOOKNAME__</name>
</book>
PHP;

    $jsonData = json_decode(file_get_contents($seedDataFile), true);
    $randomDirName = $dummyDataFolder.DIRECTORY_SEPARATOR.randomString();

    for($folderDepth = rand(3, 15); $folderDepth > 0; $folderDepth--) {
        var_dump($randomDirName);
        if ( !file_exists( $randomDirName ) && !is_dir( $randomDirName ) ) {
            if(mkdir( $randomDirName ) !== false) {
                for($randomFileNumbersInFolder = rand(2, 4); $randomFileNumbersInFolder > 0; $randomFileNumbersInFolder--) {
                    $content = str_replace(["__AUTHOR__", "__BOOKNAME__"],
                        [
                            $jsonData[array_rand($jsonData)]['author'],
                            $jsonData[array_rand($jsonData)]['title'],
                        ],
                        $xml
                    );
                    $fp = fopen($randomDirName . randomString() .".xml","wb");
                    fwrite($fp,$content);
                    fclose($fp);

                }

                $randomDirName = $randomDirName.DIRECTORY_SEPARATOR.randomString();
            }
        }
    }
}

function randomString($len = 10) {
    return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0 , $len);
}
