<?php namespace IESElCaminas;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Swift_TransportException;
use Swift_RfcComplianceException;
use IESElCaminas\MailServer;
use IESElCaminas\ReadCSV;

use IESElCaminas\Command;
use IESElCaminas\Cipher;
use IESElCaminas\Log;

/**
 * Author: Chidume Nnamdi <kurtwanger40@gmail.com>
 */
class InviteUsersCommand extends Command
{
    protected $email;
    protected $password;
    protected $config;
    public function __construct(array $config)
    {
        parent::__construct();
        $this->config = $config;
        $this->readCsv = new ReadCSV();
    
    }
    public function configure()
    {
        $this -> setName('send')
            -> setDescription("Enviar correu d'invitació a GitHub for Education");
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $ok = true;
        $this -> getCredentials($input, $output);
        $cipher = new Cipher($this->config);
        $log = Log::load($this->config['log']['channel'], __DIR__ ."/../log/log-" . time() . ".log" ,  $this->config['log']['level']);

        try{
            $mail = new MailServer($this->config, $this->email, $this->password);
        }catch(Swift_RfcComplianceException $e) {
            $output->writeln("<error>" . $e->getMessage() . "</error>");
        }catch(\Exception $e){
            $output->writeln("<error>" . $e->getMessage() . "</error>");
        }
        $generator = $this->readCsv->genCsvFile();
        $hayCSVs = false;
        foreach ($generator as $file) {
            $hayCSVs = true;
            echo $file . "\n";
            $lines = $this->readCsv->read($file);
            $log->add("Processant arxiu de log $file");
            foreach($lines as $line) {
               list($correo, $nia) = $line;
               try{
                    $hash = $cipher->getHash($nia);
                    $body = $mail->getUserEmailBody($nia, $hash);
                    $output->writeln("Enviant correu a " . $correo . " - " . $nia);
                    //$output->writeln($body);
                    $mail->send($correo, $body,  $this->email);
                    $log->add("Correu enviat a " . $correo . " - " . $nia);
                }catch (Swift_TransportException $swift_TransportException){
                    $message = "S'ha produít un error en connectar\n";
                    $message .= "Comproveu l'usuari i contrasenya\n";
                    $message .= "Assegureu-vos que heu permés al compte de correu $this->email l'accés 'lesssecureapps'\n";
                    $message .= "Més informació en https://myaccount.google.com/lesssecureapps";
                    $output->writeln("<error>$message</error>");
                    $log->add($message);
                    //echo $swift_TransportException->getCode();// . " " . $swift_TransportException->getMessage();
                    $ok = false;
                    break;
                }catch(\Exception $e){
                    $output->writeln("<error>" . $e->getMessage() . "</error>");
                    $log->add($e->getMessage());
                    $ok = false;
                    break;
                }

            }
            if (!$ok){
                $log->add("S'ha produït un error en el procés de l'arxiu de log $file");
                break;
            }else{
                $log->add("Fin procés arxiu de log $file");
                //renombrar el archivo para no volver a procesarlo
                $this->readCsv->remaneCSVFile($file);
            }
        }
        if (!$hayCSVs){
            $output->writeln("<info>No hi ha arxius que processar</info>");
        }
    }
}