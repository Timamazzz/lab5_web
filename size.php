<?php

$title = 'Size';
$message = 'только для относительного пути папки проекта';

require 'src/components/header/header.php';
require 'src/components/footer/footer.php'
?>

<section class="section">
    <div style="padding-bottom: 5%;display:flex;align-items: center;width: 100%;flex-direction: column;">
        <form id="post" method="GET" style="padding: 20px; border-radius:15px; display:flex; flex-direction:column; align-items:center; margin-bottom: 20px;" >
            <div>
                <input placeholder="Введите имя" style="margin: 20px 0 20px 0; border-radius: 10px"  type="text" name="path" id="formName" class="input__form">
            </div>
            <input type="submit">
        </form>
        <?php
        function getFilesSize($path)
        {
            $fileSize = 0;
            $dir = scandir($path);

            foreach($dir as $file)
            {
                if (($file!='.') && ($file!='..'))
                    if(is_dir($path . '/' . $file))
                        $fileSize += getFilesSize($path.'/'.$file);
                    else
                        $fileSize += filesize($path . '/' . $file);
            }

            return $fileSize;
        }

        $filename = isset($_GET['path'])? $_GET['path'] : '';

        $home = $_SERVER['DOCUMENT_ROOT'];
        $home = $home . '/aniuwu';
        $dh = opendir($home);


        while (false!==($fname=readdir($dh))){
            $files[]=$fname;
        }

        $flag = 0;
        foreach ($files as $f){
            if( $f==$filename && is_dir($filename)){
                $message = 'Размер папки : ' . getFilesSize($filename) . ' байтов';
                $flag=1;
            }
            if ($f==$filename && is_file($filename)){
                $message = 'Размер файла : ' . filesize($filename) . ' байтов';
                $flag=1;
            }
        }

        if($flag==0){
            $message = 'только для относительного пути папки проекта';
        }

        ?>
        <h4 style="color: white"><?php echo $message ?></h4>
    </div>
</section>
