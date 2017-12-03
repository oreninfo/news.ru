<?php
$db = mysql_connect("localhost", "stas", "123456") or die ("MySQL сервер недоступен!" .mysql_error());
mysql_select_db("link_bd", $db) or die ("Не удалось подключиться к базе даных!”" .mysql_error());
$hQuery = mysql_query("SELECT id, title, content_path FROM time56 WHERE title IN (SELECT title FROM time56 GROUP BY title HAVING COUNT(title)>1);");
while($aResult = mysql_fetch_assoc($hQuery))
{
   $aMas[] = $aResult;
}
For($i = 0;$i<=count($aMas);$i++)
{
    For($j = $i+1;$j<=count($aMas);$j++)
    {
        If(strcmp($aMas[$i]['title'], $aMas[$j]['title']) == 0 AND strcmp($aMas[$i]['content_path'], $aMas[$j]['content_path']) == 0)
        {
            echo $aMas[$i]['id']." == ".$aMas[$j]['id']."\r\n";
            $a = $aMas[$i]['id'];
            $b = $aMas[$j]['id'];
            //$hQuery = mysql_query("DELETE FROM time56 WHERE id='$b';");
            //$hQuery = mysql_query("UPDATE time56_category SET news_id='$a' WHERE news_id='$b';");
        }
    }
}
