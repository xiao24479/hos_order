<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form action="?c=site&m=site_up" method="post">
            start:<input type="text" value="<?=isset($info['number_s'])?$info['number_s']:0?>" name="start" />end:<input type="text" value="<?=isset($info['number_e'])?$info['number_e']:1?>" name="end" />
            <input type="submit" value="submit"/>
        </form>
    </body>
</html>
