<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TogaMysql
 *
 * @author HirotoYahagi
 */
class TogaMysql
{

  static function createDb($controller,$userName, $projectName, $num)
  {
    try
    {
      if (!self::checkName($userName))
      {
        $e = "invalid username.";
        //throw new Exception($e);
      }
    }
    catch (Exception $e)
    {
      $controller->logMessage('{TOGA} ' . $e->getMessage(), 'err');
    }
    exec("mysqladmin -u" . TogaSettings::getSqlUname() . " -p" . TogaSettings::getSqlPasswd() . " create toga-" . $userName . "_" . $projectName . "_" . $num);
  }

  function createUser($controller,$projectName,$userName, $mkey)
  {
    $userName='toga-' . $userName;
    try
    {
      if (!self::checkName($userName))
      {
        $e = "invalid username.";
        //throw new Exception($e);
      }
    }
    catch (Exception $e)
    {
      $controller->logMessage('{TOGA} ' . $e->getMessage(), 'err');
    }



    $body = "CREATE USER '" . $userName . "'@'localhost' IDENTIFIED BY  '" . $mkey . "';" .
            "GRANT USAGE ON * . * TO  '" . $userName . "'@'localhost' IDENTIFIED BY  '" . $mkey .
            "' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;" .
            "GRANT ALL PRIVILEGES ON  `" . $userName . "\\_%` . * TO  '" . $userName . "'@'localhost'; ";
    $libfs = new LibFileSystem();
    $libfs->makeFile("/tmp/toga/$userName.sql", $body);
    exec("mysql --user=" . TogaSettings::getSqlUname() ." --password=" . TogaSettings::getSqlPasswd() . '< ' . "/tmp/webide/$userName$projectName.sql");
    $libfs->removeDir("/tmp/webide/$userName.sql");
  }

  private function chackName($string)
  {
    //TODO:Make pattern
    $pattern = '';
    return preg_match($pattern, $string);
  }

}

?>
