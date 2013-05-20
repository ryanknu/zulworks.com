<?php

namespace Collaborate\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ZSmarty\SmartyModel;
use Zend\View\Model\JsonModel;
use Application\Model\BlogEntry;
use Application\Model\AppleScript;
use SplFileObject;

class IndexController extends AbstractActionController
{
    const OsaScriptNumbersExport = '/../osascript/export.txt';
    const CredentialFileName = '/Credentials-Usernames.csv';
    const BalanceFileName = '/Ledger-%s-Total.csv';
    const LedgerFileName = '/Ledger-Master Ledger.csv';
    const DetailFileName = '/Ledger-%s-Ledger.csv';
    private $dir;
    
    public function __construct()
    {
        if ( isset($_POST['dir'] ) ) {
            $this->dir = $_POST['dir'];
        }
    }
    
    public function indexAction()
    {
        $sm = new SmartyModel;
        $sm->setTerminal(true);
        return $sm;
    }
    
    public function getDbAction()
    {
        $as = new AppleScript;
        $path = __DIR__ . self::OsaScriptNumbersExport;
        $res = $as->file($path);
        $res = $res[0];
        return new JsonModel(array('res' => $res));
    }
    
    public function getKeyAction()
    {
        $user = @$_POST['user'];
        $pass = @$_POST['pass'];
        
        if ( !$user ) {
            // check cookie
            $key = @$_COOKIE['collab-key'];
            $creds = $this->getCredentials($key);
            $user = $creds->user;
            $pass = $creds->pass;
        }
        
        $data_user = $this->getUser($user);
        
        if ( $data_user != null && $pass == $data_user->pass ) {
            $key = base64_encode("$user%$pass");
            $last_16 = substr($key, -16);
            $hash = md5($last_16);
            $key .= $hash;
            setcookie('collab-key', $key, time() + 60 * 60 * 24 * 30 * 12);
            return new JsonModel(array("res" => $key, 'user' => $data_user));
        }

        return new JsonModel(array('res' => 'No'));
    }
    
    public function getBalanceAction()
    {
        $key = @$_POST['key'];
        $creds = $this->getCredentials($key);
                
        $data_user = $this->getUser($creds->user);
        
        $out = array();
        
        if ( $data_user != null && $creds->pass == $data_user->pass ) {
            foreach( explode(',', $data_user->apps) as $app ) {
                $sfo = new SplFileObject(sprintf($this->dir . self::BalanceFileName, $app), 'r');
                list($name, $balance) = $sfo->fgetcsv();
                $out[] = array('name' => $name, 'balance' => $balance);
            }
        }
        
        return new JsonModel(array('data' => $out));
    }
    
    public function getLedgerAction()
    {
        $key = @$_POST['key'];
        $creds = $this->getCredentials($key);
                
        $data_user = $this->getUser($creds->user);
        
        $out = array();
        
        if ( $data_user != null && $creds->pass == $data_user->pass ) {
            $sfo = new SplFileObject($this->dir . self::LedgerFileName, 'r');
            while(!$sfo->eof()) {
                list($name, $date, $amount, $adj_amount) = $sfo->fgetcsv();
                if ( $amount ) {
                    $out[] = array(
                        'name' => $name,
                        'date' => $date,
                        'amount' => $adj_amount,
                    );
                }
            }
            
            if ( strlen($data_user->apps) == 2 ) {
                $fo = new SplFileObject($this->dir . sprintf(self::DetailFileName, $data_user->apps), 'r');
                $payments = array();
                while (!$fo->eof()) {
                    $line = $fo->fgetcsv();
                    if ( $line[3] && $line[3] != 'Payments' ) {
                        $payments[] = array(
                            'name' => 'Payment',
                            'amount' => '(' . $line[3] . ')',
                            'date' => $line[4],
                        );
                    }
                }
                
                $out = array_merge($out, $payments);
                usort($out, function($left, $right) {
                    return strtotime($left['date']) < strtotime($right['date']);
                });
            }
        }
        
        return new JsonModel(array('data' => $out));
    }
    
    private function getCredentials($key)
    {
        $user = $pass = chr(0) + chr(1) + chr(0xAA);
        if ( $key ) {
            $hash = substr($key, -32);
            $key = substr($key, 0, strlen($key) - 32);
            $o_hash = md5(substr($key, -16));
            $key = base64_decode($key);
            if ( $o_hash == $hash ) {
                list($user, $pass) = explode('%', $key);
            }
        }
        
        return (object) array(
            'user' => $user,
            'pass' => $pass,
        );
    }
    
    private function getUser($user)
    {
        if ( !$user ) {
            return null;
        }
        
        $cred_file = $this->dir . self::CredentialFileName;
        $fo = new SplFileObject($cred_file, 'r');
        while( !$fo->eof() ) {
            list($f_user, $f_pass, $f_apps) = $fo->fgetcsv();
            if ( strcasecmp($f_user, $user) == 0 ) {
                return (object) array(
                    'user' => $f_user,
                    'pass' => $f_pass,
                    'apps' => $f_apps,
                );
            }
        }
        return null;
    }
    
}
